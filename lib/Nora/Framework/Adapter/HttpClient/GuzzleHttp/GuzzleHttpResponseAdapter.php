<?php
namespace Nora\Framework\Adapter\HttpClient\GuzzleHttp;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Nora\Client\Http\HttpClient;
use Nora\Client\Http\Request;
use Nora\Client\Http\ResponseInterface;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\Extension\HttpClientInterface;

class GuzzleHttpResponseAdapter implements ResponseInterface
{
    private $response;


    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getBody()
    {
        return $this->response->getBody();
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function __toString()
    {
        return (String) $this->getBody();
    }
}
