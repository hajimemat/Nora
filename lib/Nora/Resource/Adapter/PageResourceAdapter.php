<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Resource\Adapter;

use Nora\DI\Exception\Unbound;
use Nora\DI\Injector\InjectorInterface;
use Nora\Kernel\Helper\ContextHelper;
use Nora\Kernel\Helper\SharedKernelHelper;
use Nora\Resource\Exception\ResourceNotFoundException;
use Nora\Resource\Uri;

class PageResourceAdapter
{
    use SharedKernelHelper;

    /**
     * @var InjectorInterface
     */
    private $injector;

    private $path = '';

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    public function get(Uri $uri) : ResourceObject
    {
        $ns = $this->getNamespace();

        $path = str_replace('-', '', ucwords($uri->path, '/-'));
        $class = sprintf(
            '%s%s\\Resource\\%s',
            $ns,
            $this->path,
            str_replace('/', '\\', ucwords($uri->scheme) . $path)
        );
        try {
            $instance = $this->injector->getInstance($class);
        } catch (Unbound $e) {
            throw $this->getNotFound($uri, $e, $class);
        }

        return $instance;
    }

    private function getNotFound(Uri $uri, Unbound $e, string $class)
    {
        $unboundClass = $e->getMessage();
        if ($unboundClass === "{$class}-") {
            return new ResourceNotFoundException((string) $uri, 404, $e);
        }

        return $e;
    }
}
