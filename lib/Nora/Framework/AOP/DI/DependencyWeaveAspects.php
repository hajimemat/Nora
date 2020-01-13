<?php

namespace Nora\Framework\AOP\DI;

use Nora\Framework\AOP\Advice\MethodInterceptor;
use Nora\Framework\AOP\Bind\Bind;
use Nora\Framework\AOP\Compiler\CompilerInterface;
use Nora\Framework\AOP\WeavedInterface;
use ReflectionClass;

trait DependencyWeaveAspects
{
    public function weaveAspects(CompilerInterface $compiler, array $pointcuts)
    {
        $class = (string) $this->instantiator;
        $isInterceptor = (new ReflectionClass($class))->implementsInterface(MethodInterceptor::class);
        $isWeaved = (new ReflectionClass($class))->implementsInterface(WeavedInterface::class);
        if ($isInterceptor || $isWeaved) {
            return;
        }
        $bind = new Bind();
        $bind->bind($class, $pointcuts);
        if (!$bind->getBindings()) {
            return;
        }
        $class = $compiler->compile($class, $bind);
        $this->instantiator->weaveAspects($class, $bind);
    }
}
