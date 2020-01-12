<?php
/**
 * this file is part of Nora
 *
 * @package Resource
 */
declare(strict_types=1);

namespace Nora\Resource\Request;

use Nora\Resource\ResourceObjectInterface;

class Request
{
    public $query = [];
    public $method;
    public $invoker;
    public $resourceObject;

    public function __construct(
        InvokerInterface $invoker,
        ResourceObjectInterface $resourceObject,
        string $method = Request::GET
    ) {
        $this->resourceObject = $resourceObject;
        $this->method = $method;
        $this->invoker = $invoker;
    }

    public function __invoke($query = [])
    {
        $this->query = array_merge($this->query, $query);
        $this->resourceObject->uri->query = $this->query;

        $response = $this->invoker->invoke($this);
        return $response;
    }

    public function __get($name)
    {
        return ($this)()->body[$name] ?? null;
    }

    public function addQuery($query)
    {
        $this->query = array_merge($this->query, $query);
        return $this;
    }

    public function __toString()
    {
        $uri = clone $this->resourceObject->uri;
        $uri->query = $this->query;
        return (string) $uri;
    }
}
