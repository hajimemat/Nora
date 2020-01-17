<?php
namespace Nora\Framework\Adapter\HttpClient\GuzzleHttp;

use GuzzleHttp\Client;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\Extension\HttpClientInterface;

class GuzzleHttpConfigurator extends AbstractConfigurator
{
    public function configure()
    {
        $this
            ->bind(HttpClientInterface::class)
            ->toProvider(GuzzleHttpClientProvider::class);
    }
}
