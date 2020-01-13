<?php
namespace NoraFake;

use Nora\Framework\AOP\Compiler\Compiler;
use Nora\Framework\DI\Configuration\AbstractConfigurator;

class FakeMainConfigurator extends FakeConfigurator
{
    public function configure()
    {
        parent::configure();
        $this->override(new FakeSubConfigurator());
    }
}
