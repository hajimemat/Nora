<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Framework\DI;

use ArrayIterator;
use IteratorAggregate;
use Nora\Framework\AOP\Compiler\CompilerInterface;
use Nora\Framework\AOP\Pointcut\Pointcut;
use Nora\Framework\DI\Container\ContainerInterface;
use ArrayAccess;
use Nora\Framework\DI\Dependency\Dependency;
use Nora\Framework\DI\Exception\Unbound;
use Nora\Framework\DI\Exception\Untargeted;
use Nora\Framework\DI\ValueObject\Name;
use ReflectionClass;

class Container implements ContainerInterface, ArrayAccess, IteratorAggregate
{
    /**
     * @var Dependency[]
     */
    private $container = [];

    /**
     * @var Pointcut[]
     */
    private $pointcuts = [];

    /**
     * Add Binding
     */
    public function add(Bind $bind)
    {
        $dependency = $bind->getBound();
        $dependency->register($this, $bind);
    }

    /**
     * Add Pointcut
     */
    public function addPointcut(Pointcut $pointcut)
    {
        $this->pointcuts[] = $pointcut;
    }

    public function getPointcuts() : array
    {
        return $this->pointcuts;
    }

    public function getInstance(string $interface, string $name = Name::ANY)
    {
        return $this->getDependency($interface.'-'.$name);
    }

    public function getDependency(string $index)
    {
        if (!isset($this->container[$index])) {
            throw $this->unbound($index);
        }
        $dependency = $this->container[$index];
        return $dependency->inject($this);
    }

    public function unbound(string $index)
    {
        list($class, $name) = explode('-', $index);
        if (class_exists($class) && !(new ReflectionClass($class))->isAbstract()) {
            return new Untargeted($class);
        }
        return new Unbound("{$class}-{$name}");
    }

    public function offsetSet($name, $value)
    {
        $this->container[$name] = $value;
    }

    public function offsetGet($name)
    {
        return $this->container[$name];
    }

    public function offsetExists($name)
    {
        return array_key_exists($name, $this->container);
    }

    public function offsetUnset($name)
    {
        unset($name);
        throw new InvalidMethodCall(__CLASS__ . " not supported unset");
    }

    public function __invoke($interface, $name = Name::ANY)
    {
        return $this->getInstance($interface, $name);
    }

    public function weaveAspects(CompilerInterface $compiler)
    {
        foreach ($this->container as $dependency) {
            if (!$dependency instanceof Dependency) {
                continue;
            }
            $dependency->weaveAspects($compiler, $this->pointcuts);
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->container);
    }
}
