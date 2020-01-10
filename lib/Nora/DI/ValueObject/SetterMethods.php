<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\ValueObject;

use InvalidArgumentException;
use Nora\DI\Container;
use ReflectionParameter;

final class SetterMethods
{
    /**
     * @var array
     */
    private $setterMethods;

    public function __construct(array $setterMethods)
    {
        $this->setterMethods = $setterMethods;
    }

    public function __invoke($instance, Container $container)
    {
        foreach ($this->setterMethods as $setterMethod) {
            $setterMethod($instance, $container);
        }
    }
}
