<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\AOP\Bind;

interface BindInterface
{
    public function bind(string $class, array $pointcuts);
    public function bindInterceptors(string $method, array $interceptors);
    public function getBindings();
    public function toString($salt);
}
