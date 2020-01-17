<?php
namespace Nora\Framework\Adapter\Misc\Elasticsearch;

use Nora\Framework\DI\Configuration\AbstractConfigurator;

class ElasticsearchConfigurator extends AbstractConfigurator
{
    public function configure()
    {
        $this->bind(ElasticsearchClientInterface::class)
            ->toProvider(ElasticsearchClientProvider::class);
    }
}
