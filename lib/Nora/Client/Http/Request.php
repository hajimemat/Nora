<?php
namespace Nora\Client\Http;

use GuzzleHttp\Client;
use Nora\Client\Http\HttpClient;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\Extension\HttpClientInterface;

class Request
{
    private $client;

    public $method;
    public $url;
    public $query;
    public $headers = [];

    public $response;

    public function __construct(callable $invoker)
    {
        $this->invoker = $invoker;
    }

    public function get($url) : self
    {
        $this->method = HttpClient::GET;
        $this->url = $url;
        return $this;
    }

    public function put($url) : self
    {
        $this->method = HttpClient::PUT;
        $this->url = $url;
        return $this;
    }

    public function withQuery(array $query) : self
    {
        $this->query = $query;
        return $this;
    }
    public function withHeaders(array $headers) : self
    {
        $this->headers = $headers;
        return $this;
    }
    public function withJson(array $data) : self
    {
        return $this->withBody(json_encode($data));
    }

    public function withBody(string $data)
    {
        $this->body = $data;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function request()
    {
        $this->response = ($this->invoker)($this);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->getResponse();
    }

    public function getParams()
    {
        return $this->query;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getResponse()
    {
        if (!($this->response instanceof ResponseInterface)) {
            $this->request();
        }
        return $this->response;
    }
}
