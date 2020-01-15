<?php
namespace Nora\Framework\Adapter\Logger\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Nora\Framework\Kernel\AbstractKernelConfigurator;
use Psr\Log\LoggerInterface;

class MonologConfigurator extends AbstractKernelConfigurator
{
    public function configure()
    {
        $this->bind(LoggerInterface::class)
             ->toProvider(
                 MonologLoggerProvider::class
             );
    }
}
