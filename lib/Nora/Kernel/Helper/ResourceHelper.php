<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Helper;

use Nora\Kernel\Context\Context;
use Nora\DI\Annotation\Inject;
use Nora\Resource\ResourceInterface;

trait ResourceHelper
{
    public $resource;

    /**
     * @Inject
     */
    public function setResource(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }
}
