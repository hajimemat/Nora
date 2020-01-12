<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Resource\Helper;

use Nora\DI\Annotation\Inject;
use Nora\Resource\Renderer\RendererInterface;

trait ResourceHelper
{
    /**
     * @var ResourceInterface
     */
    public $resource;

    /**
     * @Inject
     */
    public function setResouce(ResourceInterface $resource = null)
    {
        $this->resource = $resource;
    }
}
