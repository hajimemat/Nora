<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Provid;

use Nora\DI\Module\AbstractModule;
use Nora\Kernel\Context\Context;
use Nora\Resource\UriFactory;
use Nora\Router\Router;
use Nora\Router\RouterInterface;

class RouterModule extends AbstractModule
{
    public function configure()
    {
        $this->bind(RouterInterface::class)->to(Router::class);
        $this->bind(UriFactory::class);
    }
}
