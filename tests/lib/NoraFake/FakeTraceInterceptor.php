<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace NoraFake;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Nora\Framework\AOP\Advice\MethodInterceptor;
use Nora\Framework\AOP\Joinpoint\MethodInvocation;
use Nora\Framework\DI\Annotation\Named;

final class FakeTraceInterceptor implements MethodInterceptor
{
    /**
     * @Named("data=data")
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     */
    public function invoke(MethodInvocation $invocation)
    {
        $instance = $invocation->getThis();
        $method = $invocation->getMethod();
        $result = $invocation->proceed();
        return sprintf(
            "(trace) ".preg_replace_callback(
                '/\{(.+)\}/',
                function ($m) {
                    return $this->data[$m[1]];
                },
                $result
            )
        );
    }
}
