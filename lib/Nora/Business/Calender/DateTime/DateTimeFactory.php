<?php
namespace Nora\Business\Calender\DateTime;

use ReflectionClass;

class DateTimeFactory
{
    public function __invoke(string $class)
    {
        $refClass = new ReflectionClass($class);
        if (!$refClass->implementsInterface(DateTimeInterface::class)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s is not implements %s',
                    $class,
                    DateTimeInterface::class
                )
            );
        }

        return $refClass->newInstance();
    }
}
