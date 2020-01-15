<?php
namespace Nora\Framework\Kernel\Provide\Vars;

use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\Provide\Vars\DotEnv\EnvLoader;

class VarsConfigurator extends AbstractConfigurator
{
    public function configure()
    {
        $this
            ->bind()
            ->annotatedWith('_SERVER')
            ->toInstance($_SERVER);
        $this
            ->bind(EnvLoader::class);
        $this
            ->bind()
            ->annotatedWith('_GET')
            ->toInstance($_GET);
        $this
            ->bind()
            ->annotatedWith('_POST')
            ->toInstance($_POST);
        $this
            ->bind(Vars::class);
        $this
            ->bind()
            ->annotatedWith("project_root")
            ->toInstance('/tmp');
    }
}
