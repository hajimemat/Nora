<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\AOP\Compiler;

use Nora\Framework\AOP\Bind\BindInterface;

final class SpyCompiler implements CompilerInterface
{
    /**
     * {@inheritdoc}
     */
    public function newInstance(string $class, array $args, BindInterface $bind)
    {
    }
    /**
     * {@inheritdoc}
     */
    public function compile(string $class, BindInterface $bind) : string
    {
        if ($this->hasNoBinding($class, $bind)) {
            return $class;
        }
        return $class . $this->getInterceptors($bind);
    }
    private function hasNoBinding($class, BindInterface $bind) : bool
    {
        $hasMethod = $this->hasBoundMethod($class, $bind);
        return ! $bind->getBindings() && ! $hasMethod;
    }
    private function hasBoundMethod(string $class, BindInterface $bind) : bool
    {
        $bindingMethods = array_keys($bind->getBindings());
        $hasMethod = false;
        foreach ($bindingMethods as $bindingMethod) {
            if (method_exists($class, $bindingMethod)) {
                $hasMethod = true;
            }
        }
        return $hasMethod;
    }
    private function getInterceptors(BindInterface $bind) : string
    {
        $bindings = $bind->getBindings();
        if (! $bindings) {
            return '';
        }
        $log = ' (aop)';
        foreach ($bindings as $mehtod => $intepceptors) {
            $log .= sprintf(
                ' +%s(%s)',
                $mehtod,
                implode(', ', $intepceptors)
            );
        }
        return $log;
    }
}
