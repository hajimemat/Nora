<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI;

use Nora\DI\Exception\InvalidProvider;
use Nora\DI\Exception\NotFound;
use ReflectionClass;

class BindValidation
{
    public function constructor(string $interface)
    {
        if ($interface && !interface_exists($interface) && !class_exists($interface)) {
            throw new NotFound($interface);
        }
    }

    public function to(string $interface, string $class) : ReflectionClass
    {
        if (!class_exists($class)) {
            throw new NotFound($class);
        }
        if (interface_exists($interface) && !(new ReflectionClass($class))->implementsInterface($interface)) {
            $msg ="[{$class}] is not implemented [{$interface}] interface";
            throw new InvalidType($msg);
        }
        return new ReflectionClass($class);
    }

    public function toProvider(string $provider) : ReflectionClass
    {
        if (!class_exists($provider)) {
            throw new NotFound($provider);
        }
        if (!(new ReflectionClass($provider))->implementsInterface(ProviderInterface::class)) {
            throw new InvalidProvider($provider);
        }

        return new ReflectionClass($provider);
    }
}
