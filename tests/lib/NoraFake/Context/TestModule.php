<?php
namespace NoraFake\Context;

use NoraFake\FakeComponent;
use Nora\DI\Module\AbstractModule;
use Nora\DI\Annotation\Inject;
use Nora\DI\Annotation\Named;

class TestModule extends AbstractModule
{
    public function configure()
    {
        $this->bind(FakeComponent::class);
    }
}
