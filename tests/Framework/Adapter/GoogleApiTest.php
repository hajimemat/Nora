<?php
namespace Nora\Framework\Adapter;

use Nora\Framework\Adapter\Misc\Elasticsearch\ElasticsearchClientInterface;
use Nora\Framework\DI\InjectorInterface;
use Nora\Framework\Kernel\Context\AppContext;
use Nora\Framework\Kernel\KernelInjector;
use Nora\Framework\Bootstrap;
use Nora\Framework\Kernel\KernelInterface;
use Nora\Integration\Google\GoogleClientInterface;
use Nora\Integration\Google\InlineAuthenticate;
use Nora\Integration\Google\OAuth\GoogleOauthScope;
use Nora\Integration\Google\OAuth\Scope;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class GoogleApiTest extends TestCase
{
    /**
     * @test
     */
    public function カーネルロード()
    {
        $kernel = (new Bootstrap)('NoraFake', 'app-test');
        $this->assertInstanceOf(\NoraFake\Kernel\Kernel::class, $kernel);
        return $kernel;
    }

    /**
     * @test
     * @depends カーネルロード
     */
    public function GoogleClient拡張のロード($kernel)
    {
        $this->assertInstanceOf(GoogleClientInterface::class, $kernel->google);
        return $kernel->google;
    }

    /**
     * @test
     * @depends カーネルロード
     */
    public function oauthのテスト($kernel)
    {
        $client = $kernel->google;
        //
        // Elasticsearchの設定
        //  (var/sample/install.php)
        //
        // Elasticsearchにアクセストークンを作成
        //  (var/sample/create_google_access_token.php)
        //
        
        // アクセストークンを取得する
        $result = $kernel->elasticsearch->get([
            'index' => 'google',
            'id' => 'test_token'
        ]);

        $tokenResult = $result;
        $token = $tokenResult['_source'];
        // アクセストークンをセットする
        $client->setAccessToken($result['_source']);
        if ($client->isAccessTokenExpired()) { // 有効期限がきれる
            var_Dump('aaaa');
            $client->refreshToken($result['_source']['refresh_token']);
        }

        // OAUTH2サービス起動
        $service = $client->getOAuth2Service();
        $userinfo = $service->userinfo->get();
        //
        $result = $kernel->elasticsearch->index([
            'index' => 'google',
            'id' => $userinfo->id,
            'body' => array_merge((array)$userinfo, [
                'type' => 'userinfo',
                'token_uuid' => $token['token_uuid']
            ])
        ]);
        $result = $kernel->elasticsearch->index([
            'index' => 'google',
            'id' => $tokenResult['_id'],
            'body' => array_merge($tokenResult['_source'], [
                'google_user_id' => $userinfo->id,
            ])
        ]);
 
        $result = $kernel->elasticsearch->get([
            'index' => 'google',
            'id' => 'test_token'
        ]);
        $result = $kernel->elasticsearch->search([
            'index' => 'google',
            'body' => [
                'query' => [
                    'term' => [
                        'token_uuid' => $token['token_uuid']
                    ],
                ]
            ]
        ]);
        var_dump($result);
        die();
        //
        // $result = $kernel->elasticsearch->get([
        //     'index' => 'google_users',
        //     'id' => $userinfo->id,
        // ]);
        //
        // var_dump($result);
    }
}
