<?php
namespace NoraFake;

use Nora\DI\Module\AbstractModule;
use PHPUnit\Framework\TestCase;

class FakeModule extends AbstractModule
{
    public function configure()
    {
        $this->bind(Fake::class);
    }
}
