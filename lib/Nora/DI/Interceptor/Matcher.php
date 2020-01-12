<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\Interceptor;

class Matcher implements MatcherInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \ReflectionException
     */
    public function any()
    {
        return new BuiltinMatcher(__FUNCTION__, []);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \ReflectionException
     */
    public function annotatedWith($annotationName) : AbstractMatcher
    {
        if (! class_exists($annotationName)) {
            throw new InvalidAnnotationException($annotationName);
        }
        return new AnnotatedMatcher(__FUNCTION__, [$annotationName]);
    }
}
