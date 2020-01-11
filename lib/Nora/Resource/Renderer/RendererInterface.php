<?php
/**
 * this file is part of Nora
 *
 * @package Resource
 */
declare(strict_types=1);

namespace Nora\Resource\Renderer;

use Nora\Resource\ResourceObjectInterface;

interface RendererInterface
{
    public function render(ResourceObjectInterface $ro);
}
