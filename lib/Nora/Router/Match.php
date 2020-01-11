<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Router;

class Match
{
    private $method = 'get';
    private $path = '/';
    private $query = [];

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getQuery()
    {
        return $this->query;
    }


    public function __toString()
    {
        return "{$this->method} {$this->path}" . (empty($this->query) ? '': '?'.http_build_query($this->query));
    }
}
