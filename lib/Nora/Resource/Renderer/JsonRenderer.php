<?php
/**
 * this file is part of Nora
 *
 * @package Resource
 */
declare(strict_types=1);

namespace Nora\Resource\Renderer;

use InvalidArgumentException;
use Nora\DI\Annotation\Named;
use Nora\Resource\ResourceObjectInterface;
use ReflectionClass;

class JsonRenderer implements RendererInterface
{
    public function render(ResourceObjectInterface $ro)
    {
        return json_encode($ro->body);
    }
}
