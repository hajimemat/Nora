<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI;

use Nora\DI\ValueObject\Name;
use Nora\DI\ValueObject\SetterMethod;
use Nora\DI\ValueObject\SetterMethods;
use ReflectionMethod;

class InjectionPoints
{
    public function __invoke(string $class) : SetterMethods
    {
        $points = [];
        foreach ($this->points as $point) {
            $points[] = $this->getSetterMethod($class, $point);
        }
        return new SetterMethods($points);
    }

    public function addMethod(string $method, string $name = Name::ANY) :self
    {
        $this->points[] = [$method, $name, false];
        return $this;
    }

    private function getSetterMethod(string $class, array $point) : SetterMethod
    {
        $setterMethod = new SetterMethod(
            new ReflectionMethod($class, $point[0]),
            new Name($point[1])
        );
        if ($point[2]) {
            $setterMethod->setOptional();
        }
        return $setterMethod;
    }
}
