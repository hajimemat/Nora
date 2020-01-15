<?php
namespace Nora\Framework\Adapter\Cache\PhpCache;

use Nora\Framework\Kernel\AbstractKernelConfigurator;
use Psr\SimpleCache\CacheInterface;

class PhpCacheConfigurator extends AbstractKernelConfigurator
{
    public function configure()
    {
        $this->bind(CacheInterface::class)
             ->toProvider(
                 PhpCacheProvider::class
             );
    }
}
