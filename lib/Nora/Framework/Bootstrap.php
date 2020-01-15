<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Framework;

use Nora\Framework\DI\InjectorInterface;
use Nora\Framework\Kernel\KernelInjector;
use Nora\Framework\Kernel\KernelInterface;
use Nora\Framework\Kernel\KernelMeta;
use Nora\System\FileSystem\CreateWritableDirectory;
use ReflectionClass;

class Bootstrap
{
    public function __invoke(
        string $name,
        string $context = 'app',
        string $directory = null,
        string $cacheNamespace = ''
    ) : KernelInterface {
        $directory = $directory ?? $this->getDirectory($name);
        $meta = new KernelMeta($name, $context, $directory);
        $meta->tmpDir = (new CreateWritableDirectory)($directory, '/var/tmp/', $context);
        $meta->logDir = (new CreateWritableDirectory)($directory, '/var/log/', $context);

        $injector = new KernelInjector($meta);
        $kernelId = $meta->name . ucwords($context) . $cacheNamespace;

        $kernel = $injector->getInstance(KernelInterface::class);
        return $kernel;
    }

    private function getDirectory(string $name) : string
    {
        $class = $name . "\\Kernel\\Kernel";
        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Invalid Class ". $class);
        }
        return dirname(
            (string) (new ReflectionClass($class))->getFileName(),
            3
        );
    }
}
