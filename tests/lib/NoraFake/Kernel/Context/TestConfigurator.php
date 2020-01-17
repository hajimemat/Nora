<?php
namespace NoraFake\Kernel\Context;

use Cache\Adapter\Apc\ApcCachePool;
use Cache\Adapter\Apcu\ApcuCachePool;
use Cache\Adapter\Common\AbstractCachePool;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Cache\Adapter\Redis\RedisCachePool;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use NoraFake\FakeComponent;
use Nora\Framework\Adapter\Cache\PhpCache\PhpCacheProvider;
use Nora\Framework\Adapter\Misc\Elasticsearch\ElasticsearchConfigurator;
use Nora\Framework\Adapter\Misc\GoogleClient\GoogleClientConfigurator;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\AbstractKernelConfigurator;
use Nora\Framework\Kernel\Provide\Vars\DotEnv\EnvLoader;
use Psr\Cache\CacheItemPoolInterface;
use Redis;

class TestConfigurator extends AbstractKernelConfigurator
{
    public function configure()
    {
        $this->bind(FakeComponent::class);

        // 環境変数処理
        // ---------------------------------------
        $this
            ->bind(EnvLoader::class)
            ->toInstance(
                (new EnvLoader($this->meta->directory))->override()
            );

        // キャッシュ
        // ---------------------------------------
        $this
            ->bind(AbstractCachePool::class)
            ->to(RedisCachePool::class);

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);

        $this
            ->bind(Redis::class)
            ->toInstance($redis);

        // ロギング設定
        // ---------------------------------------
        $appHandler = new StreamHandler(
            $this->meta->logDir . '/test.log',
            Logger::WARNING
        );
        $debugHandler = new StreamHandler(
            $this->meta->logDir . '/debug.log',
            Logger::DEBUG
        );

        $slackHandler = new SlackHandler(
            $token = getenv('SLACK_TOKEN'),
            $channel = getenv('SLACK_ROOM'),
            $name = null,
            $use_attachment = true,
            $icon_emoji = null,
            $over_notification_log_level = Logger::INFO,
            $use_bubble = true,
            $use_short_attachment = true,
            $use_context_and_extra = true
        );

        // @See For Adding Development {{{
        // https://aws.amazon.com/jp/blogs/news/php-application-logging-with-amazon-cloudwatch-logs-and-monolog/
        // $syslogFormatter = new LineFormatter(
        //     "%channel%: %level_name%: %message% %context% %extra%",
        //     null,
        //     false,
        //     true
        // );
        // $slackHandler->setFormatter($syslogFormatter);
        // $handler->setFormatter($syslogFormatter);
        // }}}

        $this->bind()
             ->annotatedWith('logger_config')
             ->toInstance([
                 'handlers' => [
                     $appHandler,
                     $debugHandler,
                     $slackHandler
                 ]
             ]);

        // Elastic設定
        // ---------------------------------------
        $this->install(new ElasticsearchConfigurator());
        $this->bind()
             ->annotatedWith('elasticsearch_hosts')
             ->toInstance(['localhost:9200']);
        // Google設定
        // ---------------------------------------
        $this->install(new GoogleClientConfigurator());
        $this->bind()
             ->annotatedWith('google_auth_config')
             ->toInstance($this->meta->directory.'/var/google-credentials.json');
    }
}
