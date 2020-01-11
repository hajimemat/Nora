<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Helper;

use Nora\Kernel\Context\Context;
use Nora\Router\RouterInterface;
use Nora\DI\Annotation\Inject;

trait RouterHelper
{
    public $router;

    /**
     * @Inject
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }
}
