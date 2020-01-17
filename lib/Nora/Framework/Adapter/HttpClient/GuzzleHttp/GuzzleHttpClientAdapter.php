<?php
namespace Nora\Framework\Adapter\HttpClient\GuzzleHttp;

use GuzzleHttp\Client;
use Nora\Client\Http\HttpClient;
use Nora\Client\Http\Request;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\Extension\HttpClientInterface;

class GuzzleHttpClientAdapter implements HttpClientInterface
{
    private $client;


    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get($url) : Request
    {
        return (new Request([$this, 'invoke']))->get($url);
    }

    public function put($url) : Request
    {
        return (new Request([$this, 'invoke']))->put($url);
    }

    public function invoke(Request $req)
    {
        $options = [];
        $url = $req->url;

        if ($req->method === HttpClient::GET) {
            $url = $req->url.($req->getParams() ? '?'.http_build_query($req->getParams()): '');
        } elseif ($req->method === HttpClient::POST) {
            $options['form_params'] = $req->getParams();
        } elseif ($req->method === HttpClient::PUT) {
            $options['json'] = json_encode($req->getBody(), true);
            $options['debug'] = true;
            var_dump($options);
        }
        $options['headers'] = $req->getHeaders();
        $res = $this->client->request(
            $req->method,
            $url,
            $options
        );

        return new GuzzleHttpResponseAdapter($res);
    }
}
