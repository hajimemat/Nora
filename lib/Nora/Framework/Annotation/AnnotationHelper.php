<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

final class AnnotationHelper
{
    /**
     * @var AnnotationReader
     */
    private $reader;
    private $class;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    public function methodAnnotation(ReflectionMethod $method) : callable
    {
        return function ($annotationClass) use ($method) {
            $result = $this->reader->getMethodAnnotation(
                $method,
                $annotationClass
            );

            if (!$result instanceof $annotationClass) {
                return false;
            }
            return $result;
        };
    }
}
