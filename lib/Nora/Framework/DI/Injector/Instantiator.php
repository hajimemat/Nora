<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Injector;

// use Nora\DI\Aop\Bind as AopBind;
// use Nora\DI\AspectBind;
// use Nora\DI\Container;
// use Nora\DI\Exception\Unbound;
use Nora\Framework\AOP\Bind\Bind as AOPBind;
use Nora\Framework\DI\AspectBind;
use Nora\Framework\DI\Container\ContainerInterface;
use Nora\Framework\DI\ValueObject\Name;
use ReflectionClass;

final class Instantiator
{
    /**
     * @var ReflectionClass
     */
    private $class;
    /**
     * @var Arguments
     */
    private $arguments;
    /**
     * @var AspectsBind
     */
    private $bind;

    /**
     * @var MethodInjectors
     */
    private $methodInjectors;

    /**
     * @var Name
     */
    private $constructorName;

    public function __construct(
        ReflectionClass $class,
        MethodInjectors $methodInjectors,
        Name $constructorName = null
    ) {
        $constructorName = $constructorName ?? new Name(Name::ANY);
        $constructor = $class->getConstructor();
        if ($constructor) {
            $this->arguments = new Arguments($constructor, $constructorName);
        }
        $this->class = $class->name;
        $this->methodInjectors = $methodInjectors;
        $this->constructorName = $constructorName;
    }

    public function __toString()
    {
        return $this->class;
    }

    public function __invoke(ContainerInterface $container)
    {
        try {
            $instance =
            $this->arguments instanceof Arguments ?
                (new ReflectionClass($this->class))->newInstanceArgs($this->arguments->inject($container)):
                new $this->class;
        } catch (Unbound $e) {
            throw new Unbound("Error for {$this->class}", 1, $e);
        }
        return $this->postNewInstance($container, $instance);
    }

    public function postNewInstance(ContainerInterface $container, $instance)
    {
        ($this->methodInjectors)($instance, $container);

        if ($this->bind instanceof AspectBind) {
            $instance->bindings = $this->bind->inject($container);
        }
        return $instance;
    }

    /**
     * @param string $class
     */
    public function weaveAspects($class, AOPBind $bind)
    {
        $this->class = $class;
        $this->bind = new AspectBind($bind);
    }
}
