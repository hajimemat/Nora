<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Injector;

use InvalidArgumentException;
use Nora\Framework\DI\Container;
use ReflectionParameter;

final class MethodInjectors
{
    /**
     * @var array
     */
    private $methodInjectors;

    public function __construct(array $methodInjectors)
    {
        $this->methodInjectors = $methodInjectors;
    }

    public function __invoke($instance, Container $container)
    {
        foreach ($this->methodInjectors as $methodInjector) {
            $methodInjector($instance, $container);
        }
    }
}
