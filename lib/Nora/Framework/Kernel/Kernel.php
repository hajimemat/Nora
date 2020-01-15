<?php
namespace Nora\Framework\Kernel;

use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\DI\InjectorInterface;
use Psr\Log\LoggerInterface;

class Kernel implements KernelInterface
{
    public $injector;

    /**
     * @Nora\Framework\DI\Annotation\Inject
     */
    public function setInjector(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }
}
