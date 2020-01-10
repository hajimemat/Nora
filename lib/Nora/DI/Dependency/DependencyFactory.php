<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\DI\Dependency;

use Doctrine\Common\Annotations\AnnotationReader;
use Nora\DI\InjectionPoints;
use Nora\DI\ValueObject\Blueprint;
use Nora\DI\ValueObject\Name;
use Nora\DI\ValueObject\NewInstance;
use Nora\DI\ValueObject\SetterMethods;
use ReflectionClass;
use ReflectionMethod;

final class DependencyFactory
{
    public function newDependency(ReflectionClass $class) : Dependency
    {
        $blueprint = new Blueprint(new AnnotationReader);
        $newInstance = $blueprint->getNewInstance($class);
        $postConstruct = $blueprint->getPostConstruct($class);

        return new Dependency($newInstance, $postConstruct);
    }

    public function newToConstructor(
        ReflectionClass $class,
        string $name,
        InjectionPoints $injectionPoints = null,
        ReflectionMethod $postConstruct = null
    ) : Dependency {
        $setterMethods = $injectionPoints ? $injectionPoints($class->name): new SetterMethods([]);
        $newInstance = new NewInstance($class, $setterMethods, new Name($name));

        return new Dependency($newInstance, $postConstruct);
    }

    public function newProvider(
        ReflectionClass $provider,
        string $context
    ) : DependencyProvider {
        $dependency = $this->newDependency($provider);
        return new DependencyProvider($dependency, $context);
    }
}
