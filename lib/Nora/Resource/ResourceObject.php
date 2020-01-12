<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Resource;

use Nora\DI\Annotation\Inject;
use Nora\Kernel\Extension\Transfer\TransferInterface;
use Nora\Resource\Renderer\RendererInterface;

class ResourceObject implements ResourceObjectInterface
{
    public $code = 200;
    public $body = [];
    public $view;
    public $uri;
    public $headers = [];

    /**
     * @var RendererInterface
     */
    public $renderer;


    /**
     * @Inject
     */
    public function setRenderer(RendererInterface $renderer = null)
    {
        $this->renderer = $renderer;
    }

    public function render()
    {
        if ($this->renderer) {
            return $this->renderer->render($this);
        }
        return json_encode($this->body);
    }

    public function __toString()
    {
        return $this->render();
    }

    public function transfer(TransferInterface $transfer, array $server)
    {
        return $transfer($this, $server);
    }

    public function __set($key, $value)
    {
        $this->body[$key] = $value;
    }

    public function offsetSet($key, $value)
    {
        $this->body[$key] = $value;
    }

    public function offsetGet($key)
    {
        return $this->body[$key];
    }

    public function offsetExists($key)
    {
        return array_key_exists($this->body, $key);
    }

    public function offsetUnset($key)
    {
        unset($this->body[$key]);
    }
}
