<?php
namespace Nora\Framework\Kernel\Context;

use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\Provide\Vars\VarsConfigurator;

class AppContext extends AbstractConfigurator
{
    public function configure()
    {
        $this->install(new VarsConfigurator());
    }
}
