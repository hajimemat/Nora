<?php
declare(strict_types=1);
namespace Nora\DI\Compiler;

use Nora\DI\Aop\BindInterface;

interface CompilerInterface
{
    /**
     * Compile class
     */
    public function compile(string $class, BindInterface $bind) : string;

    /**
     * Return new instance weaved interceptor(s)
     *
     * @return object
     */
    public function newInstance(string $class, array $args, BindInterface $bind);
}
