<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Container;

interface ContainerConfigureInterface
{
    /**
     * Do Container Configuration
     */
    public function configure();
}
