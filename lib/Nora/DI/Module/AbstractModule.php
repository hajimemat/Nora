<?php
namespace Nora\DI\Module;

use Nora\DI\Bind;
use Nora\DI\Container;

abstract class AbstractModule
{
    /**
     * @var AbstractConfigure
     */
    private $lastModule;

    public function __construct(
        self $lastModule = null
    ) {
        $this->lastModule = $lastModule;
        $this->activate();
        if ($lastModule instanceof self) {
            $this->container->merge($lastModule->getContainer());
        }
    }

    public function __toString()
    {
        return (new ModuleString)($this->getContainer(), $this->getContainer()->getPointcuts());
    }

    public function install(self $module)
    {
        $this->getContainer()->merge($module->getContainer());
    }

    public function override(self $module)
    {
        $module->getContainer()->merge($this->container);
        $this->container = $module->getContainer();
    }

    public function activate()
    {
        $this->container = new Container;
        $this->configure();
    }

    protected function bind(string $interface = '') : Bind
    {
        return new Bind($this->getContainer(), $interface);
    }

    public function getContainer() : Container
    {
        return $this->container;
    }

    abstract public function configure();
}
