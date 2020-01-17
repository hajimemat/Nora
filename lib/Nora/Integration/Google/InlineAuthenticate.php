<?php
namespace Nora\Integration\Google;

use Google_Client;
use Google\Service\Urlshortener\Url;
use Nora\Framework\DI\Annotation\Named;
use Nora\Framework\DI\Dependency\ProviderInterface;
use Nora\Integration\Google\GoogleClientInterface;

/**
 * Google OAuth 認証処理を簡単に実行する
 */
class InlineAuthenticate
{
    public function __construct()
    {
    }

    public function __invoke(GoogleClientInterface $client, array $scopes = [])
    {
        foreach ($scopes as $scope) {
            $client->addScope($scope);
        }
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setRedirectUri('http://localhost:8080');
        echo "Open browser: ".$client->createAuthUrl();
        if (ob_get_level() !== 0) {
            ob_flush();
            flush();
        }

        $query = $this->runHttpServer('tcp://0.0.0.0:8080');
        return $client->authenticate($query->code);
    }

    private function renderSuccess()
    {
        return
            '<h1>Access Granted</h1>'
            .'<blockquote>Authorization Success: close browser</blockquote>';
    }

    private function runHttpServer($host)
    {
        $server = stream_socket_server($host, $errno, $errstr);
        $sock= stream_socket_accept($server);
        while (false !== ($line = trim(fgets($sock)))) {
            if ('' === $line) {
                break;
            }
            $headers[] = $line;
        }
        fwrite($sock, 'HTTP/2.0 200 OK'.PHP_EOL);
        fwrite($sock, 'Content-Type: text/html'.PHP_EOL);
        fwrite($sock, PHP_EOL);
        fwrite($sock, $this->renderSuccess().PHP_EOL);
        fclose($sock);
        fclose($server);
        list($method, $url, $httpver) = explode(' ', $headers[0]);
        $parts = parse_url($url);
        parse_str($parts['query'], $query);
        return (object) $query;
    }
}
