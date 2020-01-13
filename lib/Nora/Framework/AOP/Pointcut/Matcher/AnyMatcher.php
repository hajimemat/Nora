<?php
declare(strict_types=1);
namespace Nora\Framework\AOP\Pointcut\Matcher;

use Nora\Framework\AOP\Pointcut\AbstractMatcher;

final class AnyMatcher extends AbstractMatcher
{
    /**
     * @var array
     */
    private static $builtinMethods = [];
    /**
     * @throws \ReflectionException
     */
    public function __construct()
    {
        parent::__construct();
        if (self::$builtinMethods === []) {
            $this->setBuildInMethods();
        }
    }
    /**
     * {@inheritdoc}
     */
    public function matchesClass(\ReflectionClass $class, array $arguments) : bool
    {
        unset($class, $arguments);
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function matchesMethod(\ReflectionMethod $method, array $arguments) : bool
    {
        unset($arguments);
        return ! ($this->isMagicMethod($method->name) || $this->isBuiltinMethod($method->name));
    }
    /**
     * @throws \ReflectionException
     */
    private function setBuildInMethods()
    {
        $methods = (new \ReflectionClass('\ArrayObject'))->getMethods();
        foreach ($methods as $method) {
            self::$builtinMethods[] = $method->name;
        }
    }
    private function isMagicMethod(string $name) : bool
    {
        return strpos($name, '__') === 0;
    }
    private function isBuiltinMethod(string $name) : bool
    {
        return in_array($name, self::$builtinMethods, true);
    }
}
