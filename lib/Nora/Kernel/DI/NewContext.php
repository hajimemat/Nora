<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\DI;

use Nora\DI\Module\AbstractModule;
use Nora\Kernel\Context\Context;

final class NewContext
{
    public function __invoke(string $namespace, string $context) : Context
    {
        return new Context($namespace, $context);
    }
}
