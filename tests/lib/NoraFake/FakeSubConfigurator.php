<?php
namespace NoraFake;

use Nora\Framework\AOP\Compiler\Compiler;
use Nora\Framework\DI\Configuration\AbstractConfigurator;

class FakeSubConfigurator extends AbstractConfigurator
{
    public function configure()
    {
        $this->bind()
             ->annotatedWith('data')
             ->toInstance([
                'name' => 'kurari'
             ]);
    }
}
