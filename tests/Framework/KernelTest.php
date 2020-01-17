<?php
namespace Nora\Framework;

use Nora\Framework\DI\InjectorInterface;
use Nora\Framework\Kernel\Context\AppContext;
use Nora\Framework\Kernel\KernelInjector;
use Nora\Framework\Bootstrap;
use Nora\Framework\Kernel\KernelInterface;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    private $container;

    /**
     * @test
     */
    public function カーネルロード()
    {
        $kernel = (new Bootstrap)('NoraFake', 'app-test');
        $this->assertInstanceOf(\NoraFake\Kernel\Kernel::class, $kernel);

        return $kernel;
    }

    /**
     * @test
     * @depends カーネルロード
     */
    public function カーネルのdiが有効か(KernelInterface $kernel)
    {
        $this->assertInstanceOf(InjectorInterface::class, $kernel->injector);
    }

    /**
     * @test
     * @depends カーネルロード
     */
    public function カーネルコンテクストが有効か(KernelInterface $kernel)
    {
        $this->assertArrayHasKey('app', $kernel->injector->loadedContexts);
        $this->assertEquals(AppContext::class, $kernel->injector->loadedContexts['app']);
    }

    /**
     * @test
     * @depends カーネルロード
     */
    public function 環境変数オーバーライドが有効か(KernelInterface $kernel)
    {
        $v = $kernel->vars;
        $this->assertEquals('some secret dayo', $v->env['SOME_SECRET']);
        $kernel->cache->set("aaa", "bbb");
        $this->assertEquals('bbb', $kernel->cache->get('aaa'));
    }

    /**
     * @test
     * @depends カーネルロード
     */
    public function カーネルキャッシュ(KernelInterface $kernel)
    {
        $kernel->cache->set("aaa", "bbb");
        $this->assertEquals('bbb', $kernel->cache->get('aaa'));
    }

    /**
     * @test
     * @depends カーネルロード
     */
    public function カーネルログ(KernelInterface $kernel)
    {
        @unlink($kernel->meta->directory.'/var/log/app-test/test.log');
        $kernel->emergency('緊急事態');
        $kernel->alert('警告');
        $kernel->critical('致命的エラー');
        $kernel->error('エラー');
        $kernel->warning('ワーニング');
        $size = filesize($kernel->meta->directory.'/var/log/app-test/test.log');
        $this->assertGreaterThan(100, $size);
    }

    /**
        $kernel->emergency('緊急事態');
     * @test
     * @depends カーネルロード
     */
    public function カーネルHttpクライアント(KernelInterface $kernel)
    {
        // $status = $kernel
        //     ->http
        //     ->get('http://127.0.0.1:9200/_cat/nodes')
        //     ->withQuery([
        //         'v' => 'true',
        //         'pretty' => 'true'
        //     ]);
        // $create = $kernel
        //     ->http
        //     ->put('http://127.0.0.1:9200/abc')
        //     ->withHeaders([
        //         'Content-Type' => 'application/json'
        //     ]);
        // $index = $kernel
        //     ->http
        //     ->get('http://127.0.0.1:9200/_cat/indices')
        //     ->withHeaders([
        //         'Content-Type' => 'application/json'
        //     ]);
        //
        // $this->assertEquals(200, $index->getResponse()->getStatusCode());
        //
        // $add = $kernel
        //     ->http
        //     ->put('http://127.0.0.1:9200/abc/_doc/1?pretty')
        //     ->withHeaders([
        //         'Content-Type' => 'application/json'
        //     ])
        //     ->withJson([
        //         'name' => 'hoge'
        //     ]);
        //
    }
}
