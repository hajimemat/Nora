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
}
