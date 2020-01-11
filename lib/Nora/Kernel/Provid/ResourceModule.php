<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Provid;

use Nora\DI\Module\AbstractModule;
use Nora\Resource\Adapter\AdapterFactory;
use Nora\Resource\Resource;
use Nora\Resource\ResourceInterface;

class ResourceModule extends AbstractModule
{
    public function configure()
    {
        $this->bind(ResourceInterface::class)->to(Resource::class);
        $this->bind(AdapterFactory::class);
    }
}
