<?php
namespace NoraApp\Resource\Page\Home;

use Nora\Kernel\Helper\SharedKernelHelper;
use Nora\Kernel\Kernel as Base;
use Nora\Resource\ResourceObject;

class Name extends ResourceObject
{
    public function onGet()
    {
        $this->name = 'hoge';

        return $this;
    }

    public function __set($key, $value)
    {
        $this->body[$key] = $value;
    }
}
