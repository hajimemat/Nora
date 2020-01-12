<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\ValueObject;

use Nora\DI\Aop\Bind as AopBind;
use Nora\DI\AspectBind;
use Nora\DI\Container;
use Nora\DI\Exception\Unbound;
use ReflectionClass;

final class NewInstance
{
    /**
     * @var ReflectionClass
     */
    private $class;
    /**
     * @var SetterMethods
     */
    private $setterMethods;
    /**
     * @var Arguments
     */
    private $arguments;
    /**
     * @var AspectsBind
     */
    private $bind;

    public function __construct(
        ReflectionClass $class,
        SetterMethods $setterMethods,
        Name $constructorName = null
    ) {
        $constructorName = $constructorName ?? new Name(Name::ANY);
        $constructor = $class->getConstructor();
        if ($constructor) {
            $this->arguments = new Arguments($constructor, $constructorName);
        }
        $this->setterMethods = $setterMethods;
        $this->class = $class->name;
    }

    public function __toString()
    {
        return $this->class;
    }

    public function __invoke(Container $container)
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

    public function postNewInstance(Container $container, $instance)
    {
        ($this->setterMethods)($instance, $container);

        if ($this->bind instanceof AspectBind) {
            $instance->bindings = $this->bind->inject($container);
        }
        return $instance;
    }

    /**
     * @param string $class
     */
    public function weaveAspects($class, AopBind $bind)
    {
        $this->class = $class;
        $this->bind = new AspectBind($bind);
    }
}
