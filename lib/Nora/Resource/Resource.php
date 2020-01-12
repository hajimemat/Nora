<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Resource;

use Nora\Resource\Adapter\AdapterFactory;
use Nora\Resource\Exception\InvalidRequestMethod;
use Nora\Resource\Request\RequestFactory;

class Resource implements ResourceInterface
{
    /**
     * @var UriFactory
     */
    private $uriFactory;


    private $method;

    /**
     * @var AdapterFactory
     */
    private $adapterFactory;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    public function __construct(
        UriFactory $uriFactory,
        AdapterFactory $adapterFactory,
        RequestFactory $requestFactory
    ) {
        $this->uriFactory = $uriFactory;
        $this->adapterFactory = $adapterFactory;
        $this->requestFactory = $requestFactory;
    }

    public function uri(string $uri)
    {
        $uri = ($this->uriFactory)($uri);
        $resourceObject = $this->newInstance($uri);
        return ($this->requestFactory)($resourceObject, $this->method);
    }

    public function newInstance(Uri $uri) : ResourceObject
    {
        $ro = ($this->adapterFactory)($uri->scheme)->get($uri);
        $ro->uri = $uri;
        return $ro;
    }

    public function __get($method)
    {
        if (in_array($method, ['get'])) {
            $this->method = $method;
            return $this;
        }

        throw new InvalidRequestMethod($name);
    }
}
