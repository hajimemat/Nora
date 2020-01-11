<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Provid\Html;

use Nora\DI\Module\AbstractModule;
use Nora\Resource\Renderer\HtmlRenderer;
use Nora\Resource\Renderer\RendererInterface;

class HtmlModule extends AbstractModule
{
    public function configure()
    {
        $this->bind(RendererInterface::class)->to(HtmlRenderer::class);
    }
}
