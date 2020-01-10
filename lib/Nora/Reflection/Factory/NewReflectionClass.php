<?php
/**
 * this file is part of Nora
 *
 * @package Reflection
 */
declare(strict_types=1);

namespace Nora\Reflection\Factory;

use Nora\Reflection\Exception\ClassNotFound;
use ReflectionClass;

final class NewReflectionClass
{
    public function __invoke(string $class) : ReflectionClass
    {
        if (!class_exists($class)) {
            throw new ClassNotFound($class);
        }

        return new ReflectionClass($class);
    }
}
