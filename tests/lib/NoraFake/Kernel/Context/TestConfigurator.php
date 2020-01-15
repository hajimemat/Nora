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
        $this
            ->bind(EnvLoader::class)
            ->toInstance(
                (new EnvLoader($this->meta->directory))->override()
            );
        
        // キャッシュ
        $this
            ->bind(AbstractCachePool::class)
            ->to(RedisCachePool::class);

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);

        $this
            ->bind(Redis::class)
            ->toInstance($redis);

        // ロギング設定
        $appHandler = new StreamHandler(
            $this->meta->logDir . '/test.log',
            Logger::WARNING
        );

        $slackHandler = new SlackHandler(
            getenv('SLACK_TOKEN'),
            getenv('SLACK_ROOM'),
            null,
            true,
            null,
            Logger::ALERT,
        );

        // @See For Adding Development {{{
        // https://aws.amazon.com/jp/blogs/news/php-application-logging-with-amazon-cloudwatch-logs-and-monolog/
        // $syslogFormatter = new LineFormatter(
        //     "%channel%: %level_name%: %message% %context% %extra%",
        //     null,
        //     false,
        //     true
        // );
        // $handler->setFormatter($syslogFormatter);
        // }}}

        $this->bind()
             ->annotatedWith('logger_config')
             ->toInstance([
                 'handlers' => [
                     $appHandler,
                     $slackHandler
                 ]
             ]);
    }
}
