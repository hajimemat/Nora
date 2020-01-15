<?php
declare(strict_types=1);
namespace Nora\Framework\DI\Injector;

interface InjectionPointInterface
{
    /**
     * Return parameter reflection
     */
    public function getParameter() : \ReflectionParameter;
    /**
     * Return method reflection
     */
    public function getMethod() : \ReflectionMethod;
    /**
     * Return class reflection
     */
    public function getClass() : \ReflectionClass;
    /**
     * Return Qualifier annotations
     */
    public function getQualifiers() : array;
}
