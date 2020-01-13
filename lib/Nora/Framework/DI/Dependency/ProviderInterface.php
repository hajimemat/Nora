<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Dependency;

interface ProviderInterface
{
    public function get();
}
