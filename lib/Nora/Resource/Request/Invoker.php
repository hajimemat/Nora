<?php
/**
 * this file is part of Nora
 *
 * @package Resource
 */
declare(strict_types=1);

namespace Nora\Resource\Request;

use Nora\Resource\ResourceObjectInterface;

class Invoker implements InvokerInterface
{
    public function invoke(Request $request)
    {
        $callable = [$request->resourceObject, 'on'.ucfirst($request->method)];
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(
                "Method Not Allowed {$request->method} in {$request->resourceObject->uri}"
            );
        }
        $params = [];
        $response = call_user_func_array($callable, $params);

        if (!$response instanceof ResourceObjectInterface) {
            $request->resourceObject->body = $response;
            $response = $request->resourceObject;
        }

        return $response;
    }
}
