<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Provid\Error;

use Nora\DI\Module\AbstractModule;
use Nora\Kernel\Extension\Error\ErrorHandlerInterface;

class ErrorModule extends AbstractModule
{
    public function configure()
    {
        $this->bind(ErrorHandlerInterface::class)->to(ErrorHandler::class);
    }
}
