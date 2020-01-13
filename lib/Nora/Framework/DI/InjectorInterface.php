<?php
declare(strict_types=1);
namespace Nora\Framework\DI;

use Nora\Framework\DI\ValueObject\Name;

interface InjectorInterface
{
    /**
     * Return instance by interface + name (interface namespace)
     *
     * @param string $interface
     * @param string $name
     */
    public function getInstance($interface, $name = Name::ANY);
}
