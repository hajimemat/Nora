<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\DI\Dependency;

use Nora\DI\Bind;
use Nora\DI\Container;
use Nora\DI\ValueObject\NewInstance;
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

    public function inject(Container $container)
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

    public function register(array &$container, Bind $bind)
    {
        $container[(string) $bind] = $bind->getBound();
    }
}
