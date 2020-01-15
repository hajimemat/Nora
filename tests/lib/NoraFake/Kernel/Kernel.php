<?php
namespace NoraFake\Kernel;

use NoraFake\FakeComponent;
use Nora\Framework\DI\InjectorInterface;
use Nora\Framework\Kernel\Kernel as Base;
use Nora\Framework\Kernel\KernelInterface;
use Nora\Framework\Kernel\KernelMeta;
use Nora\Framework\Kernel\Provide\Logger\LoggerTrait;
use Nora\Framework\Kernel\Provide\Vars\Vars;
use Psr\SimpleCache\CacheInterface;

class Kernel implements KernelInterface
{
    public $injector;
    public $vars;
    public $cache;

    // ログを使用可能にする
    use LoggerTrait;

    public function __construct(
        FakeComponent $fake,
        InjectorInterface $injector,
        KernelMeta $meta,
        Vars $vars,
        CacheInterface $cache
    ) {
        $this->injector = $injector;
        $this->vars = $vars;
        $this->cache = $cache;
    }
}
