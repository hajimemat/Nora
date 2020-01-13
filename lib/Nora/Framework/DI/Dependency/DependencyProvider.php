<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Dependency;

use Nora\Framework\DI\Bind;
use Nora\Framework\DI\Container\ContainerInterface;
use Nora\Framework\DI\ValueObject\Scope;
use ReflectionMethod;

final class DependencyProvider implements DependencyInterface
{
    /**
     * @var Dependency
     */
    private $dependency;
    /**
     * @var string
     */
    private $context;

    private $isSingleton = false;
    private $instance;

    public function __construct(Dependency $dependency, string $context)
    {
        $this->dependency = $dependency;
        $this->context = $context;
    }

    public function __sleep()
    {
        return ['context', 'dependency', 'isSingleton'];
    }

    public function __toString()
    {
        return sprintf(
            '(provider) %s',
            (string) $this->dependency
        );
    }

    public function inject(ContainerInterface $container)
    {
        if ($this->isSingleton && $this->instance) {
            return $this->instance;
        }
        $provider = $this->dependency->inject($container);
        if ($provider instanceof SetContextInterface) {
            $this->setContext($provider);
        }
        $this->instance = $provider->get();
        return $this->instance;
    }

    public function register(ContainerInterface $container, Bind $bind)
    {
        $container[(string) $bind] = $bind->getBound();
    }

    public function setScope($scope)
    {
        if ($scope === Scope::SINGLETON) {
            $this->isSingleton = true;
        }
    }
}
