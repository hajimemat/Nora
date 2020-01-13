<?php
namespace NoraFake;

use Nora\Framework\DI\Annotation\Inject;
use Nora\Framework\DI\Annotation\PostConstruct;

class FakeLang
{
    public $comp;

    /**
     * @Inject
     */
    public function setLangHoge(FakeComponent $comp)
    {
        $this->comp = $comp;
    }
}
