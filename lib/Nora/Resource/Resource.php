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

    public function __construct(
        UriFactory $uriFactory,
        AdapterFactory $adapterFactory
    ) {
        $this->uriFactory = $uriFactory;
        $this->adapterFactory = $adapterFactory;
    }

    public function uri(string $uri)
    {
        $uri = ($this->uriFactory)($uri);
        $resourceObject = $this->newInstance($uri);
        return new ResourceRequest($uri);
    }

    public function newInstance(Uri $uri) : ResourceObject
    {
        return ($this->adapterFactory)($uri->scheme)->get($uri);
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
