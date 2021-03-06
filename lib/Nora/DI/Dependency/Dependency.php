<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\DI\Dependency;

use Nora\DI\Bind;
use Nora\DI\Aop\Bind as AopBind;
use Nora\DI\Compiler\CompilerInterface;
use Nora\DI\Constant\Scope;
use Nora\DI\Container;
use Nora\DI\Interceptor\MethodInterceptorInterface;
use Nora\DI\Interceptor\WeavedInterface;
use Nora\DI\ValueObject\NewInstance;
use ReflectionClass;
use ReflectionMethod;

final class Dependency implements DependencyInterface
{
    /**
     * @var NewInstance
     */
    private $newInstance;
    /**
     * @var ReflectionMethod
     */
    private $postConstruct;
    /**
     * @var bool
     */
    private $isSingleton = false;
    private $instance;

    public function __construct(
        NewInstance $newInstance,
        ReflectionMethod $postConstruct = null
    ) {
        $this->newInstance = $newInstance;
        $this->postConstruct = $postConstruct ? $postConstruct->name: null;
    }

    public function __sleep()
    {
        return ['newInstance', 'postConstruct', 'isSingleton'];
    }

    public function __toString()
    {
        return sprintf('(dependency) %s', (string) $this->newInstance);
    }

    public function register(array &$container, Bind $bind)
    {
        $this->index = $index = (string) $bind;
        $container[$index] = $bind->getBound();
    }

    public function inject(Container $container)
    {
        if ($this->isSingleton === true && $this->instance) {
            return $this->instance;
        }

        $this->instance = ($this->newInstance)($container);
        if ($this->postConstruct) {
            $this->instance->{$this->postConstruct}();
        }
        return $this->instance;
    }

    public function setScope($scope)
    {
        if ($scope === Scope::SINGLETON) {
            $this->isSingleton = true;
        }
    }

    public function weaveAspects(CompilerInterface $compiler, array $pointcuts)
    {
        $class = (string) $this->newInstance;
        $isInterceptor = (new ReflectionClass($class))->implementsInterface(MethodInterceptorInterface::class);
        $isWeaved = (new ReflectionClass($class))->implementsInterface(WeavedInterface::class);
        if ($isInterceptor || $isWeaved) {
            return;
        }
        $bind = new AopBind;
        $bind->bind((string) $this->newInstance, $pointcuts);
        if (! $bind->getBindings()) {
            return;
        }
        $class = $compiler->compile((string) $this->newInstance, $bind);
        $this->newInstance->weaveAspects($class, $bind);
    }
}
