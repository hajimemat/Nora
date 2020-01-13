<?php
namespace Nora\Framework\DI;

use NoraFake\FakeComponent;
use NoraFake\FakeComponent2;
use NoraFake\FakeDatetime;
use NoraFake\FakeDatetimeInterface;
use NoraFake\FakeDatetimeProvider;
use NoraFake\FakeLang;
use NoraFake\FakeMessage;
use NoraFake\FakeMessageInterface;
use NoraFake\FakeMyName;
use NoraFake\FakeTrace;
use NoraFake\FakeTraceClient;
use NoraFake\FakeTraceInterceptor;
use Nora\Framework\AOP\Compiler\Compiler;
use Nora\Framework\AOP\Compiler\SpyCompiler;
use Nora\Framework\AOP\Pointcut\Matcher;
use Nora\Framework\AOP\Pointcut\Pointcut;
use Nora\Framework\DI\Container\ContainerInterface;
use Nora\Framework\DI\Dependency\Dependency;
use Nora\Framework\DI\Injector\InjectionPoints;
use Nora\Framework\DI\ValueObject\Scope;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    private $container;

    /**
     * @test
     */
    public function コンテナ作成()
    {
        $this->container = new Container();
        $this->assertInstanceOf(ContainerInterface::class, $this->container);

        return $this->container;
    }

    /**
     * @test
     * @depends コンテナ作成
     */
    public function 束縛を追加(ContainerInterface $container)
    {
        // アンターゲット束縛の作成
        (new Bind($container, FakeComponent::class));
        // 完了
        $this->assertTrue(isset($container[FakeComponent::class.'-']));

        return $container;
    }

    /**
     * @test
     * @depends 束縛を追加
     */
    public function オブジェクトを生成(ContainerInterface $container)
    {
        $instance1 = $container->getInstance(FakeComponent::class);
        $instance2 = $container->getInstance(FakeComponent::class);

        $this->assertInstanceOf(FakeComponent::class, $instance1);
        $this->assertNotSame($instance1, $instance2);
    }

    /**
     * @test
     */
    public function シングルトンテスト()
    {
        // 作り直す
        $container = new Container();
        (new Bind($container, FakeComponent::class))->in(Scope::SINGLETON);
        $instance1 = $container->getInstance(FakeComponent::class);
        $instance2 = $container->getInstance(FakeComponent::class);
        $this->assertSame($instance1, $instance2);
    }

    /**
     * @test
     */
    public function 名前付き束縛()
    {
        // 作り直す
        $container = new Container();
        (new Bind($container, FakeComponent::class))
            ->annotatedWith('comp1')
            ->in(Scope::SINGLETON);
        $instance1 = $container->getInstance(FakeComponent::class, 'comp1');
        $this->assertInstanceOf(FakeComponent::class, $instance1);
    }

    /**
     * @test
     */
    public function リンク束縛()
    {
        // 作り直す
        $container = new Container();
        (new Bind($container, FakeComponent::class))
            ->to(FakeComponent2::class)
            ->in(Scope::SINGLETON);
        $instance1 = $container->getInstance(FakeComponent::class);
        $this->assertInstanceOf(FakeComponent2::class, $instance1);
    }

    /**
     * @test
     */
    public function インスタンス束縛()
    {
        // 作り直す
        $container = new Container();
        (new Bind($container, FakeMessageInterface::class))
            ->toInstance(new FakeMessage());

        $this->assertEquals(
            'hello',
            $container->getInstance(FakeMessageInterface::class)->say()
        );
    }

    /**
     * @test
     */
    public function プロバイダ束縛()
    {
        // 作り直す
        $container = new Container();
        (new Bind($container, FakeDatetimeInterface::class))
            ->toProvider(FakeDatetimeProvider::class);

        $this->assertInstanceOf(FakeDatetime::class, $container->getInstance(FakeDatetimeInterface::class));
        $fakeDateTime = $container->getInstance(FakeDatetimeInterface::class);
        $this->assertTrue($fakeDateTime('1983-01-18')->isBirthday());
        $this->assertFalse($fakeDateTime('1983-01-15')->isBirthday());
        $this->assertTrue($fakeDateTime('1983-01-15')->isHoliday());
        $this->assertEquals('成人の日', $fakeDateTime('1983-01-15')->getHoliday());
        $this->assertEquals('体育の日', $fakeDateTime('1996-10-10')->getHoliday());
    }

    /**
     * @test
     */
    public function セッターインジェクション()
    {
        // 作り直す
        $container = new Container();
        (new Bind($container, FakeLang::class))
            ->to(FakeLang::class);
        (new Bind($container, FakeComponent::class));

        $instance = $container(FakeLang::class);

        $this->assertInstanceOf(FakeComponent::class, $instance->comp);
    }

    /**
     * @test
     */
    public function コンストラクタ束縛()
    {
        $container = new Container();
        (new Bind($container, FakeComponent::class));
        (new Bind($container, FakeMyName::class))
            ->toConstructor(
                FakeMyName::class,
                "name=name",
                (new InjectionPoints)
                    ->addMethod('setCompany', 'company')
                    ->addMethod('setHogeComp'),
                "initialize"
            );
        (new Bind($container, ''))->annotatedWith('company')->toInstance('Avap');
        (new Bind($container, ''))->annotatedWith('name')->toInstance('Hajime MATSUMOTO');
        $this->assertEquals('Hajime MATSUMOTO@Avap', $container(FakeMyName::class)->dispName);

        return $container;
    }

    /**
     * @test
     */
    public function アスペクトコンテナ作成()
    {
        $container = new Container();
        $matcher = new Matcher();
        $pointcut = new Pointcut(
            $matcher->any(),
            $matcher->annotatedWith(FakeTrace::class),
            [FakeTraceInterceptor::class]
        );
        (new Bind($container, FakeTraceInterceptor::class))
            ->toConstructor(
                FakeTraceInterceptor::class,
                'data=data'
            );
        (new Bind($container, ''))
            ->annotatedWith('data')
            ->toInstance([
                'name' => 'hajime'
            ]);
        (new Bind($container, FakeTraceClient::class));
        $container->addPointcut($pointcut);
        $fake = $container(FakeTraceClient::class);
        // Before Intercept
        $this->assertEquals('aaa {name} bbb', $fake->intercepted());
        return $container;
    }
    /**
     * @test
     * @depends アスペクトコンテナ作成
     */
    public function メソッドインターセプター(ContainerInterface $container)
    {
        $container->weaveAspects(new Compiler('/tmp'));

        $fake = $container(FakeTraceClient::class);

        $this->assertEquals('(trace) aaa hajime bbb', $fake->intercepted());

        return $container;
    }

    /**
     * @test
     * @depends アスペクトコンテナ作成
     */
    public function デバッグ(ContainerInterface $container)
    {
        $pointcuts = $container->getPointcuts();
        $spy = new SpyCompiler();
        $logs = [];
        foreach ($container as $index => $dependency) {
            if ($dependency instanceof Dependency) {
                $dependency->weaveAspects($spy, $pointcuts);
            }
            $logs[] = sprintf(
                '%s => %s',
                $index,
                (string) $dependency
            );
        }
        $this->assertEquals('-data => (array)', $logs[1]);
        $container->weaveAspects($spy);
    }
}
