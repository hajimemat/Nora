<?php
namespace NoraFake;

use Nora\Framework\AOP\Compiler\Compiler;
use Nora\Framework\DI\Configuration\AbstractConfigurator;

class FakeConfigurator extends AbstractConfigurator
{
    public function configure()
    {
        $this->bind()
             ->annotatedWith('data')
             ->toInstance([
                'name' => 'hajime'
             ]);
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(FakeTrace::class),
            [FakeTraceInterceptor::class]
        );
        $this->bind(FakeTraceClient::class);
    }
}
