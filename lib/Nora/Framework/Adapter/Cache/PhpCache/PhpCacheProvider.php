<?php
namespace Nora\Framework\Adapter\Cache\PhpCache;

use Cache\Adapter\Common\AbstractCachePool;
use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use Cache\Namespaced\NamespacedCachePool;
use Cache\Prefixed\PrefixedCachePool;
use Nora\Framework\DI\Annotation\Named;
use Nora\Framework\DI\Dependency\ProviderInterface;
use Nora\Framework\DI\Injector\InjectionPoint;
use Nora\Framework\DI\Injector\InjectionPointInterface;
use Nora\Framework\Kernel\KernelMeta;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use ReflectionClass;

class PhpCacheProvider implements ProviderInterface
{
    private $ip;
    private $meta;

    /**
     */
    public function __construct(
        InjectionPointInterface $ip,
        AbstractCachePool $pool
    ) {
        $this->ip = $ip;
        $this->name = str_replace(
            '\\',
            '_',
            $ip->getClass()->name
        );
        $this->pool = $pool;
    }

    public function get()
    {
        return new SimpleCacheBridge(
            new PrefixedCachePool($this->pool, $this->name.'-')
        );
    }
}
