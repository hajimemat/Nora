<?php
/**
 * this file is part of Nora
 *
 * @package Resource
 */
declare(strict_types=1);

namespace Nora\Resource\Request;

use Nora\Resource\ResourceObjectInterface;

class RequestFactory
{
    private $invoker;

    public function __construct(InvokerInterface $invoker)
    {
        $this->invoker = $invoker;
    }

    public function __invoke(
        ResourceObjectInterface $resourceObject,
        string $method = Request::GET
    ) : Request {
        return new Request($this->invoker, $resourceObject, $method);
    }
}
