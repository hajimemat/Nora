<?php
namespace Nora\Framework\Kernel\Provide\Vars;

use Nora\Framework\DI\Annotation\Named;
use Nora\Framework\DI\Annotation\Inject;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\Provide\Vars\DotEnv\EnvLoader;

class Vars
{
    public $server;
    public $get;
    public $post;
    public $env;

    /**
     * @Named("server=_SERVER, get=_GET, post=_POST")
     * @Inject
     */
    public function __construct($server, $get, $post, EnvLoader $env)
    {
        $this->server = $server;
        $this->get    = $get;
        $this->post   = $post;
        $this->env    = $env();
    }
}
