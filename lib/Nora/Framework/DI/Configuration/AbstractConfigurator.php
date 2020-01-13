<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Configuration;

use Nora\Framework\AOP\Pointcut\AbstractMatcher;
use Nora\Framework\AOP\Pointcut\Matcher;
use Nora\Framework\AOP\Pointcut\Pointcut;
use Nora\Framework\DI\Bind;
use Nora\Framework\DI\Container;
use Nora\Framework\DI\Container\ContainerInterface;
use Nora\Framework\DI\ValueObject\Scope;

abstract class AbstractConfigurator
{
    protected $matcher;
    protected $lastConfigurator;
    private $container;

    public function __construct(
        self $configurator = null
    ) {
        $this->lastConfigurator = $configurator;
        $this->activate();
        if ($configurator instanceof self) {
            $this->container->merge($configurator->getContainer());
        }
    }

    public function __toString()
    {
        return (new ConfiguratorString)($this->getContainer(), $this->getContainer()->getPointcuts());
    }

    public function getContainer() : ContainerInterface
    {
        return $this->container;
    }

    public function install(self $configurator)
    {
        $this->getContainer()->merge($configurator->getContainer());
    }

    public function override(self $configurator)
    {
        $configurator->getContainer()->merge($this->container);
        $this->container = $configurator->getContainer();
    }

    /**
     * Do Container Configuration
     */
    abstract public function configure();

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
        $this->getContainer()->addPointcut($pointcut);
        foreach ($interceptors as $interceptor) {
            $this->bind($interceptor)->to($interceptor)->in(Scope::SINGLETON);
        }
    }

    public function activate()
    {
        $this->container = new Container();
        $this->matcher = new Matcher();
        $this->configure();
    }
}
