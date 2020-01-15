<?php
namespace Nora\Framework\Kernel\Provide\Vars\DotEnv;

use Nora\Framework\DI\Annotation\Named;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\Provide\Vars\Dotenv\DotenvLoader;

class EnvLoader
{
    /**
     * @Named("root=project_root")
     */
    public function __construct($root)
    {
        $dotenv = new DotEnv($root, ".env");
        $dotenv->override();
    }

    public function __invoke()
    {
        return getenv();
    }
}
