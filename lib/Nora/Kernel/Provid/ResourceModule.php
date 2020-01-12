<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Provid;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use NoraApp\Resource\Page\Home\TheWellcomeMessage;
use Nora\DI\Interceptor\EmbedInterceptor;
use Nora\DI\Module\AbstractModule;
use Nora\Resource\Adapter\AdapterFactory;
use Nora\Resource\Annotation\Embed;
use Nora\Resource\Request\Invoker;
use Nora\Resource\Request\InvokerInterface;
use Nora\Resource\Request\RequestFactory;
use Nora\Resource\Resource;
use Nora\Resource\ResourceInterface;

class ResourceModule extends AbstractModule
{
    public function configure()
    {
        $this->bind(ResourceInterface::class)->to(Resource::class);
        $this->bind(AdapterFactory::class);
        $this->bind(RequestFactory::class);
        $this->bind(InvokerInterface::class)->to(Invoker::class);
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(Embed::class),
            [EmbedInterceptor::class]
        );
    }
}
