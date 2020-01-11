<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Extension\Error;

use Exception;

interface ErrorHandlerInterface
{
    public function handle(Exception $e);
}
