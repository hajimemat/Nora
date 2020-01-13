<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Inject implements InjectInterface
{
    /**
     * @var bool
     */
    public $optional = false;

    public function isOptional() : bool
    {
        return $this->optional;
    }
}
