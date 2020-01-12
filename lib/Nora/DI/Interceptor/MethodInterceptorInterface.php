<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\Interceptor;

use Nora\DI\Aop\MethodInvocation;

interface MethodInterceptorInterface extends InterceptorInterface
{
    public function invoke(MethodInvocation $invocation);
}
