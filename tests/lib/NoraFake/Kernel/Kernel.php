<?php
namespace NoraFake\Kernel;

use NoraFake\FakeComponent;
use Nora\Framework\Adapter\Misc\Elasticsearch\ElasticsearchClientInterface;
use Nora\Framework\DI\InjectorInterface;
use Nora\Framework\Kernel\Extension\HttpClientInterface;
use Nora\Framework\Kernel\Kernel as Base;
use Nora\Framework\Kernel\KernelInterface;
use Nora\Framework\Kernel\KernelMeta;
use Nora\Framework\Kernel\Provide\Logger\LoggerTrait;
use Nora\Framework\Kernel\Provide\Vars\Vars;
use Nora\Integration\Google\GoogleClientInterface;
use Psr\SimpleCache\CacheInterface;

class Kernel implements KernelInterface
{
    public $injector;
    public $vars;
    public $cache;
    public $http;
    public $meta;
    public $elasticsearch;
    public $google;

    // ログを使用可能にする
    use LoggerTrait;

    public function __construct(
        FakeComponent $fake,
        InjectorInterface $injector,
        KernelMeta $meta,
        Vars $vars,
        CacheInterface $cache,
        HttpClientInterface $httpClient,
        ElasticsearchClientInterface $elastic,
        GoogleClientInterface $google
    ) {
        $this->injector = $injector;
        $this->vars = $vars;
        $this->cache = $cache;
        $this->http = $httpClient;
        $this->meta = $meta;
        $this->elasticsearch = $elastic;
        $this->google = $google;
    }
}
