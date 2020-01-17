<?php
namespace Nora\Framework\Adapter\Misc\GoogleClient;

use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Integration\Google\GoogleClientInterface;

use Google_Client;
use Google_Service_Oauth2;

class GoogleClientAdapter implements GoogleClientInterface
{
    /**
     * @var Google_Client
     */
    public $gClient;

    public function __construct(Google_Client $gClient)
    {
        $this->gClient = $gClient;
    }

    /**
     * OAuthServiceを取得する
     */
    public function getOAuth2Service()
    {
        return new Google_Service_Oauth2($this->gClient);
    }

    public function __call($name, $params)
    {
        return call_user_func_array(
            [$this->gClient, $name],
            $params
        );
    }
}
