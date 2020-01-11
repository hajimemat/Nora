<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Helper;

use Nora\DI\Annotation\Inject;
use Nora\Kernel\Context\Context;

trait ContextHelper
{
    public $context;

    /**
     * @Inject
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function getNamespace()
    {
        return $this->context->getNamespace();
    }
}
