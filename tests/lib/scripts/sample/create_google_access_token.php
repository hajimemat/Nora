<?php

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Nora\Integration\Google\InlineAuthenticate;
use Nora\Integration\Google\OAuth\GoogleOauthScope;
use Ramsey\Uuid\Uuid;

$kernel = include dirname(__DIR__).'/bootstrap.php';

// アクセストークンを取得する
$token = ((new InlineAuthenticate)(
    $kernel->google,
    [
        GoogleOauthScope::USERINFO_PROFILE,
        GoogleOauthScope::USERINFO_EMAIL,
        GoogleOauthScope::BUSINESS_MANAGE
    ]
));

// アクセストークンを保存する
$kernel->elasticsearch->index([
    'index' => 'google',
    'id' => 'test_token',
    'body' => array_merge($token, [
        'type' => 'credential',
        'token_uuid' => Uuid::uuid4()
    ])
]);

// RefreshTokenでトークンをアップロードする場合
// $kernel->google->setAccessToken($result['_source']);
// if ($kernel->google->isAccessTokenExpired()) { // 有効期限をチェック
//     $kernel->google->refreshToken($result['_source']['refresh_token']);
// }
