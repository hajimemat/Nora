<?php
namespace Nora\Framework\Adapter\Misc\Elasticsearch;

use Elasticsearch\ClientBuilder;
use Nora\Framework\DI\Annotation\Named;
use Nora\Framework\DI\Dependency\ProviderInterface;

class ElasticsearchClientAdapter implements ElasticsearchClientInterface
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function __call($name, $args)
    {
        return call_user_func_array(
            [$this->client, $name],
            $args
        );
    }
}
