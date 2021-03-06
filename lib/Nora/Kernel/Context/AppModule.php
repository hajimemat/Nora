<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Context;

use Nora\DI\Module\AbstractModule;
use Nora\Kernel\Context\Context;
use Nora\Kernel\Provid\Error\ErrorModule;
use Nora\Kernel\Provid\Html\HtmlModule;
use Nora\Kernel\Provid\ResourceModule;
use Nora\Kernel\Provid\RouterModule;
use Nora\Kernel\Provid\Transfer\HttpModule;
use Nora\Resource\Renderer\JsonRenderer;
use Nora\Resource\Renderer\RendererInterface;

class AppModule extends AbstractModule
{
    public function configure()
    {
        $this->install(new RouterModule);
        $this->install(new ResourceModule);
        $this->install(new ErrorModule);
        $this->install(new HttpModule);
        $this->bind(RendererInterface::class)->to(JsonRenderer::class);
    }
}
