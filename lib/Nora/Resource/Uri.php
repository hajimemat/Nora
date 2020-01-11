<?php
/**
 * this file is part of Nora
 *
 * @package Resource
 */
declare(strict_types=1);

namespace Nora\Resource;

use Nora\Resource\Exception\InvalidRequestMethod;

final class Uri
{
    public $scheme;
    public $host;
    public $path;
    public $query = [];
    public $method = 'get';

    public function __construct(string $uri, array $query = [])
    {
        if (count($query) !== 0) {
            $uri = uri_template($uri, $query);
        }
        $parts = (array) parse_url($uri);
        $host = isset($parts['port']) ? sprintf('%s:%s', $parts['host'] ?? '', $parts['port'] ?? ''): $parts['host'] ?? '';
        list(
            $this->scheme,
            $this->host,
            $this->path
        ) = [$parts['scheme'] ?? '', $host, $parts['path'] ?? ''];

        if (array_key_exists('query', $parts)) {
            parse_str($parts['query'], $this->query);
        }

        if (count($query) !== 0) {
            $this->query = $query + $this->query;
        }
    }

    public function __toString()
    {
        return "{$this->scheme}://{$this->host}{$this->path}" . ($this->query ? '?'.http_build_query($this->query):'');
    }
}
