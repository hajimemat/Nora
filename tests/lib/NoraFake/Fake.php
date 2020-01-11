<?php
namespace NoraFake;

use Nora\DI\Module\AbstractModule;
use Nora\DI\Annotation\Inject;
use Nora\DI\Annotation\Named;

class Fake
{
    public function saySomething()
    {
        return $this->hello;
    }

    /**
     * @Inject
     * @Named("message")
     */
    public function setMessage(string $message)
    {
        $this->hello = $message;
    }
}
