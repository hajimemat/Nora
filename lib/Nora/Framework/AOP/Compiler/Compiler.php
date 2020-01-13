<?php
declare(strict_types=1);
namespace Nora\Framework\AOP\Compiler;

use Nora\Framework\AOP\Bind\BindInterface;
use Nora\Framework\AOP\Compiler\CodeGen\CodeGen;
use Nora\Framework\AOP\Compiler\CodeGen\ParserFactory;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use ReflectionClass;

class Compiler implements CompilerInterface
{
    public $classDir;
    private $codeGen;
    private $aopClassName;

    public function __construct(string $classDir)
    {
        if (!is_writable($classDir)) {
            throw new NotWritableException($classDir);
        }
        $this->classDir = $classDir;
        $this->codeGen = new CodeGen(
            (new ParserFactory)->newInstance(),
            new BuilderFactory,
            new Standard(['shortArraySyntax' => true])
        );

        $this->aopClassName = new ClassName;
    }

    public function __sleep()
    {
        return ['classDir'];
    }

    public function __wakeup()
    {
        $this->__construct($this->classDir);
    }

    /**
     * Compile class
     */
    public function compile(string $class, BindInterface $bind) : string
    {
        if ($this->hasNoBinding($class, $bind)) {
            return $class;
        }

        $aopClassName = ($this->aopClassName)($class, $bind->toString(''));
        if (class_exists($aopClassName, false)) {
            // Hit Cache
            return $aopClassName;
        }

        $this->requireFile($aopClassName, new ReflectionClass($class), $bind);
        return $aopClassName;
    }

    private function hasNoBinding($class, BindInterface $bind) : bool
    {
        $hasMethod = $this->hasBoundMethod($class, $bind);
        return !$bind->getBindings() && !$hasMethod;
    }

    private function hasBoundMethod(string $class, BindInterface $bind) : bool
    {
        $bindingMethods = array_keys($bind->getBindings());
        $hasMethod = false;
        foreach ($bindingMethods as $bindingMethod) {
            //if (method_exists($class, $bindingMethods)) {
            if (method_exists($class, $bindingMethod)) {
                $hasMethod = true;
            }
        }
        return $hasMethod;
    }

    private function requireFile(string $aopClassName, ReflectionClass $sourceClass, BindInterface $bind) : void
    {
        $code = $this->codeGen->generate($sourceClass, $bind);
        $file = $code->save($this->classDir, $aopClassName);
        require_once $file;
        class_exists($aopClassName);
    }

    /**
     * Return new instance weaved interceptor(s)
     *
     * @return object
     */
    public function newInstance(string $class, array $args, BindInterface $bind)
    {
        $compiledClass = $this->compile($class, $bind);
        $instance = (new ReflectionClass($compiledClass))->newInstanceArgs($args);
        $instance->bindings = $bind->getBindings();
        return $instance;
    }
}
