<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\Dependency;

use Nora\DI\Bind;
use Nora\DI\Container;
use ReflectionClass;

final class Instance implements DependencyInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function register(array &$container, Bind $bind)
    {
        $index = (string) $bind;
        $container[$index] = $bind->getBound();
    }

    public function inject(Container $container)
    {
        unset($container);
        return $this->value;
    }
}
