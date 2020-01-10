<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Named
{
    /**
     * @var string
     */
    public $value;
}
