<?php
namespace NoraFake;

use Nora\DI\Annotation\Inject;
use Nora\Kernel\Context\Context;
use Nora\Kernel\KernelInterface;
use Nora\Kernel\Kernel as Base;

class Kernel extends Base
{
    public $fake;

    /**
     * @Inject
     */
    public function setFakeComponent(FakeComponent $fake)
    {
        $this->fake = $fake;
    }

    public function __toString()
    {
        return sprintf(
            '(kernel) for %s in context "%s"',
            $this->context->namespaced('Kernel'),
            $this->context
        );
    }

    public function context() : Context
    {
        return $this->context;
    }
}
