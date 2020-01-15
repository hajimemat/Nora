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
        $kernel->emergency('緊急事態');
        $kernel->alert('警告');
        $kernel->critical('致命的エラー');
        $kernel->error('エラー');
        $kernel->warning('ワーニング');
    }
}
