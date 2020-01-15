<?php
namespace Nora\Framework\Kernel\Context;

use Nora\Framework\Adapter\Cache\PhpCache\PhpCacheConfigurator;
use Nora\Framework\Adapter\Logger\Monolog\MonologConfigurator;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\AbstractKernelConfigurator;
use Nora\Framework\Kernel\Provide\Vars\VarsConfigurator;

class AppContext extends AbstractKernelConfigurator
{
    public function configure()
    {
        $this->install(new VarsConfigurator());
        $this->install(new MonologConfigurator($this->meta));
        $this->install(new PhpCacheConfigurator($this->meta));
    }
}
