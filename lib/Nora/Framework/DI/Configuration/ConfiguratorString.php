<?php
declare(strict_types=1);
namespace Nora\Framework\DI\Configuration;

use Nora\Framework\AOP\Compiler\SpyCompiler;
use Nora\Framework\DI\Container\ContainerInterface;

final class ConfiguratorString
{
    public function __invoke(ContainerInterface $container, array $pointcuts) : string
    {
        $log = [];
        $contaier = unserialize(serialize($container));
        $spy = new SpyCompiler;
        foreach ($contaier as $dependencyIndex => $dependency) {
            if ($dependency instanceof Dependency) {
                $dependency->weaveAspects($spy, $pointcuts);
            }
            $log[] = sprintf(
                '%s => %s',
                $dependencyIndex,
                (string) $dependency
            );
        }
        sort($log);
        return implode(PHP_EOL, $log);
    }
}
