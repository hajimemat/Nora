<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Injector;

use Nora\Framework\DI\ValueObject\Name;
use ReflectionMethod;

class InjectionPoints
{
    public function __invoke(string $class) : MethodInjectors
    {
        $points = [];
        foreach ($this->points as $point) {
            $points[] = $this->getMethodInjector($class, $point);
        }
        return new MethodInjectors($points);
    }

    public function addMethod(string $method, string $name = Name::ANY) :self
    {
        $this->points[] = [$method, $name, false];
        return $this;
    }

    private function getMethodInjector(string $class, array $point) : MethodInjector
    {
        $methodInjector = new MethodInjector(
            new ReflectionMethod($class, $point[0]),
            new Name($point[1])
        );
        if ($point[2]) {
            $methodInjector->setOptional();
        }
        return $methodInjector;
    }
}
