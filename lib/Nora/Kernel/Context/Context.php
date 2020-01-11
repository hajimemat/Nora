<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Context;

use Nora\DI\Module\AbstractModule;
use Nora\Kernel\Context\Context;

final class Context
{
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var string
     */
    private $context;
    /**
     * @var array
     */
    public $globals;
    /**
     * @var array
     */
    public $env;
    /**
     * @var array
     */
    public $server;


    public function __construct(string $namespace, string $context)
    {
        $this->context = $context;
        $this->namespace = rtrim($namespace, '\\');
        $this->globals = $GLOBALS;
        $this->env = $_ENV;
        $this->server = $_SERVER;
    }

    public function __toString()
    {
        return $this->context;
    }

    public function getContextStringArray() : array
    {
        return explode("-", $this->context);
    }

    public function namespaced(...$items)
    {
        return $this->namespace ."\\". implode("\\", $items);
    }

    public function getNamespace()
    {
        return $this->namespace;
    }
}
