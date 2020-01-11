<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Helper;

use Closure;
use Nora\DI\Annotation\Inject;
use Nora\DI\Injector\InjectorInterface;
use Nora\Kernel\Kernel;
use Nora\Kernel\KernelInterface;

trait SharedKernelHelper
{
    public $kernel;

    /**
     * @Inject
     */
    public function withKernel(InjectorInterface $injector)
    {
        $this->kernel = function () use ($injector) {
            return $injector->getInstance(KernelInterface::class);
        };
    }

    public function getKernel()
    {
        if ($this->kernel instanceof Closure) {
            $this->kernel = ($this->kernel)();
        }
        return $this->kernel;
    }

    public function getNamespace()
    {
        return $this->getKernel()->getNamespace();
    }
}
