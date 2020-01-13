<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Container;

use Nora\Framework\DI\Bind;

interface ContainerInterface
{
    /**
     * Add New Binding
     */
    public function add(Bind $bind);
}
