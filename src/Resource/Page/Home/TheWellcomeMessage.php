<?php
namespace NoraApp\Resource\Page\Home;

use Nora\Kernel\Helper\SharedKernelHelper;
use Nora\Kernel\Kernel as Base;
use Nora\Resource\Annotation\Embed;
use Nora\Resource\Renderer\JsonRenderer;
use Nora\Resource\ResourceObject;

/**
 * Application Kernel
 */
class TheWellcomeMessage extends ResourceObject
{
    use SharedKernelHelper;

    /**
     * Get Other Resource
     */
    private function resource(string $uri)
    {
        return $this->getKernel()->resource->get->uri($uri);
    }

    /**
     * @Embed(rel="user", src="app://internal/login")
     */
    public function onGet()
    {
        $u = ($this['user'])->addQuery(['name' => 'hajime']);
        // // ログインユーザの情報を取得する
        // $res = $this->resource('/login')();
        // if ($res->code !== 200) {
        //     // ログインできていない
        //     var_Dump($res->body['name']);
        //     var_Dump($res->code);
        // }
        return [
            'name' => 'hajime',
            'message' => 'wellcome {name}'
        ];
    }
}
