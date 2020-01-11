<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Resource;

use Nora\DI\Annotation\Inject;
use Nora\Resource\Renderer\RendererInterface;

class ResourceObject implements ResourceObjectInterface
{
    public $code = 200;
    public $body = [];
    public $view;
    public $headers = [];

    /**
     * @var RendererInterface
     */
    public $renderer;

    /**
     * @Inject(optional=true)
     */
    public function setRenderer(RendererInterface $renderer)
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
}
