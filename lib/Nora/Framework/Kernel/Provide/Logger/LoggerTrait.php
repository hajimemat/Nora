<?php
namespace Nora\Framework\Kernel\Provide\Logger;

use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\DI\InjectorInterface;
use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    private $logger;

    /**
     * @Nora\Framework\DI\Annotation\Inject
     */
    final public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    final public function getLogger()
    {
        return $this->logger;
    }

    final public function emergency($message, array $context = [])
    {
        $this->logger->emergency($message, $context);
    }

    final public function alert($message, array $context = [])
    {
        $this->logger->alert($message, $context);
    }

    final public function critical($message, array $context = [])
    {
        $this->logger->critical($message, $context);
    }

    final public function error($message, array $context = [])
    {
        $this->logger->error($message, $context);
    }

    final public function warning($message, array $context = [])
    {
        $this->logger->warning($message, $context);
    }

    final public function notice($message, array $context = [])
    {
        $this->logger->notice($message, $context);
    }

    final public function info($message, array $context = [])
    {
        $this->logger->info($message, $context);
    }

    final public function debug($message, array $context = [])
    {
        $this->logger->debug($message, $context);
    }
}
