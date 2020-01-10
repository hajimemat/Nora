<?php
namespace Nora\DI;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Nora\DI\Annotation\Named;
use Nora\DI\ValueObject\Name;
use Nora\Dotenv\Exception\EnvFileNotFound;
use PHPUnit\Framework\TestCase;
use ReflectionFunction;

class HogeComponent
{
}

class HogeComponentNext
{
    /**
     * @var string
     */
    private $name;

    /**
     * @Named("name=user_name")
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

class Hoge
{
    /**
     * @var HogeComponent
     */
    public $name;
    /**
     * @var HogeComponentNext
     */
    public $next;

    public function __construct(HogeComponent $name, HogeComponentNext $next)
    {
        $this->name = $name;
        $this->next = $next;
    }

    public function setFuga(Fuga $fuga)
    {
    }
}

interface FugaInterface
{
}

class Fuga implements FugaInterface
{
}

class Piyo
{
    public $name;
    public $age;

    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function setSecret($name)
    {
        $this->secret = $name;
    }
    public function setHogeComp(HogeComponent $comp)
    {
        $this->hogeComp = $comp;
    }

    public function initialize()
    {
        $this->credential = sprintf(
            '%s:%s',
            $this->name,
            $this->secret
        );
    }

    public function getCredential()
    {
        return $this->credential;
    }
}

class FugaProvider implements ProviderInterface
{
    /**
     * @Named("config=fuga_config")
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function get()
    {
        return "aa";
    }
}


class BindTest extends TestCase
{
    private $name;
    /**
     * @var array
     */
    private $data;
    private $dataName;

    /**
     * @test
     */
    public function 名称オブジェクト()
    {
        $name = new Name("v1=v, v2=vv");

        $func = function ($v1, $v2, $v33) {
        };

        $expects = ['v', 'vv', ''];
        $rf = new ReflectionFunction($func);
        foreach ($rf->getParameters() as $i => $param) {
            $this->assertEquals($expects[$i], $name($param));
        }
    }

    /**
     * @test
     */
    public function アンターゲットバインド()
    {
        $container = new Container();
        $bind = new Bind($container, Hoge::class);

        // 文字化
        $this->assertEquals(Hoge::class.'-', (string) $bind);

        // unsetで登録されるのはなんでだろう
        unset($bind);

        (new Bind($container, ''))->annotatedWith("user_name")->toInstance('hajime');
        $hoge = $container->getInstance(Hoge::class, Name::ANY);

        $this->assertEquals('hajime', $hoge->next->getName());
    }

    /**
     * @test
     */
    public function インターフェイスバインド()
    {
        $container = new Container();
        $bind = (new Bind($container, FugaInterface::class))
            ->to(Fuga::class);

        $hoge = $container->getInstance(FugaInterface::class, Name::ANY);
        $this->assertInstanceOf(Fuga::class, $hoge);
    }

    /**
     * @test
     */
    public function コンストラクタバインド()
    {
        $container = new Container();
        (new Bind($container, HogeComponent::class));
        $bind = (new Bind($container, Piyo::class))
            ->toConstructor(
                Piyo::class,
                "name=name, age=age",
                (new InjectionPoints)
                    ->addMethod('setSecret', 'password')
                    ->addMethod('setHogeComp'),
                "initialize"
            );

        (new Bind($container, Name::ANY))->annotatedWith('name')->toInstance('hajime');
        (new Bind($container, Name::ANY))->annotatedWith('age')->toInstance(36);
        (new Bind($container, Name::ANY))->annotatedWith('password')->toInstance('this is secret');
        $piyo = $container->getInstance(Piyo::class, Name::ANY);
        $this->assertEquals('hajime:this is secret', $piyo->getCredential());
    }

    /**
     * @test
     */
    public function プロバイダバインド()
    {
        $container = new Container();
        (new Bind($container, FugaInterface::class))->toProvider(FugaProvider::class);
        (new Bind($container, ''))->annotatedWith('fuga_config')->toInstance([
            "aaa" => '1',
            "bbb" => '2'
        ]);

        $fuga = $container->getInstance(FugaInterface::class, '');

        $this->assertEquals("aa", $fuga);
    }

    /**
     * @test
     */
    public function セッターインジェクション()
    {
    }
}
