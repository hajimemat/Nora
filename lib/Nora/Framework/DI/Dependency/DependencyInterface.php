<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Dependency;

use Nora\Framework\DI\Bind;
use Nora\Framework\DI\Container\ContainerInterface;

interface DependencyInterface
{
    public function register(ContainerInterface $container, Bind $bind);
}
