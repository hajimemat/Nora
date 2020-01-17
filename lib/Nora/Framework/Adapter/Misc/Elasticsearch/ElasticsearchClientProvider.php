<?php
namespace Nora\Framework\Adapter\Misc\Elasticsearch;

use Elasticsearch\ClientBuilder;
use Nora\Framework\DI\Annotation\Named;
use Nora\Framework\DI\Dependency\ProviderInterface;
use Psr\Log\LoggerInterface;

class ElasticsearchClientProvider implements ProviderInterface
{
    /**
     * @Named("hosts=elasticsearch_hosts")
     */
    public function __construct($hosts, LoggerInterface $logger)
    {
        $this->hosts = $hosts;
        $this->logger = $logger;
    }

    public function get()
    {
        $builder = ClientBuilder::create();
        $builder->setHosts($this->hosts);
        $builder->setLogger($this->logger);
        $client = $builder->build();
        return new ElasticsearchClientAdapter($client);
    }
}
