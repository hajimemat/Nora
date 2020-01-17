<?php
namespace Nora\Framework\Adapter\Misc\GoogleClient;

use Google_Client;
use Google\Service\Urlshortener\Url;
use Nora\Framework\DI\Annotation\Named;
use Nora\Framework\DI\Dependency\ProviderInterface;
use Nora\Integration\Google\GoogleClientInterface;
use Nora\Integration\Google\InlineAuthenticate;

class GoogleClientProvider implements ProviderInterface
{
    private $application_name;
    private $developer_key;
    private $auth_config;

    /**
     * @Named("auth_config=google_auth_config");
     */
    public function __construct($auth_config)
    {
        $this->auth_config = $auth_config;
    }

    public function get() : GoogleClientInterface
    {
        // Clientを作成
        $client = new GoogleClientAdapter(
            new Google_Client()
        );

        // 設定する
        $client->setAuthConfigFile($this->auth_config);


        return $client;
    }
}
