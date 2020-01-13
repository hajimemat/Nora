<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Dependency;

use Nora\Framework\DI\Bind;
use Nora\Framework\DI\Container\ContainerInterface;
use ReflectionClass;

final class DependencyInstance implements DependencyInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        if (is_scalar($this->value)) {
            return sprintf(
                '(%s) %s',
                gettype($this->value),
                (string) $this->value
            );
        }
        if (is_object($this->value)) {
            return '(object) ' . get_class($this->value);
        }
        return '(' . gettype($this->value) . ')';
    }

    public function register(ContainerInterface $container, Bind $bind)
    {
        $index = (string) $bind;
        $container[$index] = $bind->getBound();
    }

    public function inject(ContainerInterface $container)
    {
        unset($container);
        return $this->value;
    }
}
