<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\DI;

use Nora\DI\Bind;
use Nora\DI\Constant\Scope;
use Nora\DI\Injector\Injector;
use Nora\DI\Injector\InjectorInterface;
use Nora\DI\Module\AbstractModule;
use Nora\DI\ValueObject\Name;
use Nora\Kernel\Context\Context;
use Nora\Kernel\KernelInterface;

final class KernelInjector implements InjectorInterface
{
    private $module;
    private $context;

    public function __construct(string $name = null, string $context)
    {
        $class = sprintf("%sKernel", $name);
        $this->context = (new NewContext)($name, $context);
        $this->injector = new Injector($this->setupModule());
    }

    private function setupModule()
    {
        if ($this->module instanceof AbstractModule) {
            return $this->module;
        }

        // Load Object Graph By Context
        $module = (new NewModule)($this->context);

        $container = $module->getContainer();

        // Set As A Builtin Injector
        (new Bind($container, InjectorInterface::class))
            ->toInstance($this);

        // Set KernelContext Class
        (new Bind($container, Context::class))
            ->toInstance($this->context);
        // Set Kernel Class
        (new Bind($container, KernelInterface::class))
            ->to($this->context->namespaced('Kernel'))
            ->in(Scope::SINGLETON);

        return $module;
    }

    public function getInstance(string $interface, string $name = Name::ANY)
    {
        return $this->injector->getInstance($interface, $name);
    }

    public function __invoke()
    {
        return $this->getInstance(KernelInterface::class);
    }
}
