<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI;

use Nora\Framework\AOP\Compiler\Compiler;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\DI\Configuration\NullConfigurator;
use Nora\Framework\DI\Dependency\Dependency;
use Nora\Framework\DI\Exception\Untargeted;
use Nora\Framework\DI\ValueObject\Name;

class Injector implements InjectorInterface
{
    private $classDir;
    private $container;

    public function __construct(AbstractConfigurator $configurator = null, string $tmpDir = '')
    {
        $configurator = $configurator ?? new NullConfigurator();
        $this->container = $configurator->getContainer();
        $this->classDir = is_dir($tmpDir) ? $tmpDir: sys_get_temp_dir();
        $this->container->weaveAspects(new Compiler($this->classDir));
        (new Bind($this->container, InjectorInterface::class))->toInstance($this);
    }

    public function __wakeup()
    {
        spl_autoload_register(
            function (string $class) {
                $file = sprintf('%s/%s.php', $this->classDir, str_replace('\\', '_', $class));
                if (file_exists($file)) {
                    require $file;
                }
            }
        );
    }

    public function getInstance($interface, $name = Name::ANY)
    {
        try {
            $instance = $this->container->getInstance($interface, $name);
        } catch (Untargeted $e) {
            $this->bind($interface);
            $instance = $this->getInstance($interface, $name);
        }
        return $instance;
    }

    private function bind(string $class)
    {
        new Bind($this->container, $class);
        $bound = $this->container[$class . '-' . Name::ANY];
        if ($bound instanceof Dependency) {
            $this->container->weaveAspect(new Compiler($this->classDir), $bound)->getInstance($class, Name::ANY);
        }
    }
}
