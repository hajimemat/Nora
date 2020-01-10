<?php
/**
 * this file is part of Nora
 *
 * @package Reflection
 */
declare(strict_types=1);

namespace Nora\Reflection\Factory;

use Nora\Reflection\Exception\ClassNotFound;
use ReflectionMethod;

final class NewReflectionMethod
{
    public function __invoke($object, string $method) : ReflectionMethod
    {
        if (!method_exists($object, $method)) {
            throw new MethodNotFound(sprintf('%s::%s', get_class($object), $method));
        }

        return new ReflectionMethod($object, $method);
    }
}
