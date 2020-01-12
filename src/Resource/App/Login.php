<?php
namespace NoraApp\Resource\App;

use Nora\Kernel\Helper\SharedKernelHelper;
use Nora\Kernel\Kernel as Base;
use Nora\Resource\ResourceObject;

/**
 * ログイン
 */
class Login extends ResourceObject
{
    /**
     * ログインユーザの情報を獲得する
     */
    public function onGet()
    {
        $this->name = 'hoge';
        $this->a = 'hoge';

        $this->code = 400;

        return $this;
    }
}
