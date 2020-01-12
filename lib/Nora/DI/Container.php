<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI;

use Nora\DI\Compiler\CompilerInterface;
use Nora\DI\Dependency\Dependency;
use Nora\DI\Exception\Unbound;
use Nora\DI\Exception\Untargeted;
use Nora\DI\Interceptor\Pointcut;
use ReflectionClass;

class Container
{
    /**
     * @var DependencyInterface[]
     */
    private $container = [];

    private $pointcuts = [];

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
        $this->pointcuts += array_merge($this->pointcuts, $container->getPointcuts());
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getPointcuts()
    {
        return $this->pointcuts;
    }


    public function addPointcut(Pointcut $pointcut)
    {
        $this->pointcuts[] = $pointcut;
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

    /**
     * Weave aspect to single dependency
     */
    public function weaveAspect(CompilerInterface $compiler, Dependency $dependency) : self
    {
        $dependency->weaveAspects($compiler, $this->pointcuts);
        return $this;
    }
}
