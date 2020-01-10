<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\ValueObject;

use Nora\DI\Container;
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
        $instance = $this->arguments instanceof Arguments ? (new ReflectionClass($this->class))->newInstanceArgs($this->arguments->inject($container)): new $this->class;
        return $this->postNewInstance($container, $instance);
    }

    public function postNewInstance(Container $container, $instance)
    {
        ($this->setterMethods)($instance, $container);
        return $instance;
    }
}
