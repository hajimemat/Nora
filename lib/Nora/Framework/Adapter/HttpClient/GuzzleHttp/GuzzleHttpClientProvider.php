<?php
namespace Nora\Framework\Adapter\HttpClient\GuzzleHttp;

use GuzzleHttp\Client;
use Nora\Framework\DI\Dependency\ProviderInterface;
use Nora\Framework\Kernel\Extension\HttpClientInterface;

class GuzzleHttpClientProvider implements ProviderInterface
{
    public function get() : HttpClientInterface
    {
        return new GuzzleHttpClientAdapter(new Client());
    }
}
