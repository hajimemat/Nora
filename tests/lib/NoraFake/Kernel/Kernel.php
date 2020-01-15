<?php
namespace NoraFake\Kernel;

use NoraFake\FakeComponent;
use Nora\Framework\DI\InjectorInterface;
use Nora\Framework\Kernel\Kernel as Base;
use Nora\Framework\Kernel\KernelInterface;
use Nora\Framework\Kernel\KernelMeta;
use Nora\Framework\Kernel\Provide\Vars\Vars;

class Kernel extends Base implements KernelInterface
{
    public $injector;
    public $vars;

    public function __construct(
        FakeComponent $fake,
        InjectorInterface $injector,
        KernelMeta $meta,
        Vars $vars
    ) {
        $this->injector = $injector;
        $this->vars = $vars;
    }
}
