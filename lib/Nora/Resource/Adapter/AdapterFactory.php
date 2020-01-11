<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Resource\Adapter;

use Nora\DI\Injector\InjectorInterface;

class AdapterFactory
{
    /**
     * @var InjectorInterface
     */
    private $injector;

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    public function __invoke(string $scheme)
    {
        $name = ucfirst($scheme);
        if (empty($name)) {
            throw new InvalidArgumentException("scheme must not be empty");
        }
        $name.= 'ResourceAdapter';
        return $this->injector->getInstance(__NAMESPACE__.'\\'.$name);
    }
}
