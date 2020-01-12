<?php
namespace NoraApp\Context;

use Nora\DI\Module\AbstractModule;

/**
 * Application Kernel
 */
class AppModule extends AbstractModule
{
    public function configure()
    {
        $this->install(new \Nora\Kernel\Context\AppModule($this));
    }
}
