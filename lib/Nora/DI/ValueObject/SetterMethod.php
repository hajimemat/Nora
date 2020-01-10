<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\ValueObject;

use LogicException;
use Nora\DI\Container;
use ReflectionMethod;

final class SetterMethod
{
    /**
     * @var ReflectionMethod
     */
    private $method;

    /**
     * @var Arguments
     */
    private $arguments;

    /**
     * @var bool
     */
    private $isOptional = false;

    public function __construct(ReflectionMethod $method, Name $name)
    {
        $this->method = $method->name;
        $this->arguments = new Arguments($method, $name);
    }

    public function setOptional()
    {
        $this->isOptional = true;
    }

    public function __invoke($instance, Container $container)
    {
        try {
            $parameters = $this->arguments->inject($container);
        } catch (Unbound $e) {
            if ($this->isOptional) {
                return;
            }
            throw $e;
        }
        $callable = [$instance, $this->method];
        if (!is_callable($callable)) {
            throw new LogicException();
        }
        call_user_func_array($callable, $parameters);
    }
}
