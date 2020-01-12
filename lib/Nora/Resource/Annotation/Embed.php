<?php
namespace Nora\Resource\Annotation;

use Nora\Kernel\Helper\SharedKernelHelper;
use Nora\Kernel\Kernel as Base;
use Nora\Resource\Annotation\Embed;
use Nora\Resource\Renderer\JsonRenderer;
use Nora\Resource\ResourceObject;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Embed
{
    public $rel;
    public $src;
}
