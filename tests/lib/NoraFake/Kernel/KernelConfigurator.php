<?php
namespace NoraFake\Kernel;

use Nora\Framework\DI\Configuration\AbstractConfigurator;

class KernelConfigurator extends AbstractConfigurator
{
    public function configure()
    {
        $this->bind(FakeComponent::class);
    }
}
