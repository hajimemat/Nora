<?php
namespace Nora\DI\Module;

use Nora\DI\Bind;
use Nora\DI\Constant\Scope;
use Nora\DI\Container;
use Nora\DI\Interceptor\AbstractMatcher;
use Nora\DI\Interceptor\Matcher;
use Nora\DI\Interceptor\Pointcut;

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
        $this->matcher = new Matcher;
        $this->configure();
    }

    protected function bind(string $interface = '') : Bind
    {
        return new Bind($this->getContainer(), $interface);
    }

    protected function bindInterceptor(
        AbstractMatcher $classMatcher,
        AbstractMatcher $methodMatcher,
        array $interceptors
    ) {
        $pointcut = new Pointcut($classMatcher, $methodMatcher, $interceptors);
        $this->container->addPointcut($pointcut);
        foreach ($interceptors as $interceptor) {
            (new Bind($this->container, $interceptor))->to($interceptor)->in(Scope::SINGLETON);
        }
    }

    public function getContainer() : Container
    {
        return $this->container;
    }

    abstract public function configure();
}
