<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\Injector;

use Nora\DI\Bind;
use Nora\DI\Exception\Unbound;
use Nora\DI\Exception\Untargeted;
use Nora\DI\Module\AbstractModule;
use Nora\DI\Module\NullModule;
use Nora\DI\ValueObject\Name;

class Injector implements InjectorInterface
{
    public function __construct(AbstractModule $module = null)
    {
        $module = $module ?? new NullModule();
        $this->container = $module->getContainer();
        (new Bind($this->container, InjectorInterface::class))
            ->toInstance($this);
    }

    public function getInstance($interface, $name = Name::ANY)
    {
        try {
            $instance = $this->container->getInstance($interface, $name);
        } catch (Untargeted $e) {
            $this->bind($interface);
            $instance = $this->getInstance($interface);
        }
        return $instance;
    }

    private function bind(string $class)
    {
        (new Bind($this->container, $class));
        // $bound = $this->container->getContainer()[$class . '-' .Name::ANY];
        // unset($bound);
    }
}
