<?php
namespace NoraFake\Kernel\Context;

use NoraFake\FakeComponent;
use Nora\Framework\DI\Configuration\AbstractConfigurator;

class TestConfigurator extends AbstractConfigurator
{
    public function configure()
    {
        $this->bind(FakeComponent::class);
    }
}
