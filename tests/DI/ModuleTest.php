<?php
namespace Nora\DI;

use NoraFake\Fake;
use NoraFake\FakeComponent;
use NoraFake\FakeModule;
use Nora\DI\Module\AbstractModule;
use Nora\DI\ValueObject\Name;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    /**
     * @test
     */
    public function モジュール作成()
    {
        $module = new class() extends AbstractModule {
            public function configure()
            {
                // モジュールをインストールする
                $this->install(new FakeModule());

                // メッセージをセットする
                $this->bind()->annotatedWith('message')
                    ->toInstance('I do not know');
            }
        };
        //
        $fake = $module->getContainer()->getInstance(Fake::class, Name::ANY);
        $this->assertEquals('I do not know', $fake->saySomething());
    }
}
