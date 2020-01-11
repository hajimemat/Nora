<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Resource;

class ResourceRequest
{
    public function __construct(Uri $uri)
    {
        echo $uri;
        die();
    }

    public function __invoke()
    {
    }
}
