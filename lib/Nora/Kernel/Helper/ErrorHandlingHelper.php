<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Helper;

use Nora\Kernel\Context\Context;
use Nora\Kernel\Extension\Error\ErrorHandlerInterface;
use Nora\Router\RouterInterface;
use Nora\DI\Annotation\Inject;

trait ErrorHandlingHelper
{
    public $error;

    /**
     * @Inject
     */
    public function setErrorHandler(ErrorHandlerInterface $error)
    {
        $this->error = $error;
    }
}
