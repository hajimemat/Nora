<?php
namespace Nora\DI;

use NoraFake\Fake;
use NoraFake\FakeComponent;
use NoraFake\FakeModule;
use Nora\App\KernelInterface;
use Nora\DI\Module\AbstractModule;
use Nora\DI\ValueObject\Name;
use Nora\Kernel\Context\Context;
use Nora\Kernel\DI\KernelInjector;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    /**
     * @test
     */
    public function カーネル作成()
    {
        $kernel = (new KernelInjector("NoraFake\\", "test"))(); // Kernelを取り出す

        $this->assertInstanceOf(Context::class, $kernel->context);
        $this->assertInstanceOf(FakeComponent::class, $kernel->fake);
    }
}
