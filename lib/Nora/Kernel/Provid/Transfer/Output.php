<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Provid\Transfer;

use Exception;
use Nora\DI\Module\AbstractModule;
use Nora\Resource\ResourceObject;
use Nora\Resource\ResourceObjectInterface;

class Output extends ResourceObject
{
    public function __construct(int $code, array $headers, string $view)
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->view = $view;
    }
}
