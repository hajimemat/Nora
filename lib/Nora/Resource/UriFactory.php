<?php
/**
 * this file is part of Nora
 *
 * @package Resource
 */
declare(strict_types=1);

namespace Nora\Resource;

use Nora\Resource\Exception\InvalidRequestMethod;

final class UriFactory
{
    private $schemeHost = 'page://internal';

    public function __invoke(string $uri, array $query = []) : Uri
    {
        $parsedUrl = (array) parse_url($uri);
        if (!array_key_exists('scheme', $parsedUrl)) {
            $uri = $this->schemeHost . $uri;
        }
        return new Uri($uri, $query);
    }
}
