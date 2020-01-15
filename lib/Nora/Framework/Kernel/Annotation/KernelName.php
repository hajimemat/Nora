<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\Kernel\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class KernelName
{
    /**
     * @var string
     */
    public $value;
}
