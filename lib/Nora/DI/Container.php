<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI;

use Nora\DI\Exception\Unbound;
use Nora\DI\Exception\Untargeted;
use ReflectionClass;

class Container
{
    /**
     * @var DependencyInterface[]
     */
    private $container = [];

    public function __sleep()
    {
        return ['contaner', 'pointcuts'];
    }

    public function add(Bind $bind)
    {
        $dependency = $bind->getBound();
        $dependency->register($this->container, $bind);
    }

    public function getInstance(string $interface, string $name)
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

    public function merge(self $container)
    {
        $this->container += $container->getContainer();
    }

    public function getContainer()
    {
        return $this->container;
    }
}
