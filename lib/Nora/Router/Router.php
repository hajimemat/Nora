<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Router;

class Router implements RouterInterface
{
    public function match(array $globals, array $server) : Match
    {
        $match = new Match;
        $match->setMethod(strtolower($server['REQUEST_METHOD']));
        $match->setPath(parse_url($server['REQUEST_URI'], PHP_URL_PATH));
        $match->setQuery($this->getUnsafeQuery($match->getMethod(), $globals, $server));

        return $match;
    }

    private function getUnsafeQuery(string $method, array $globals, array $server)
    {
        if ($method === 'get') {
            return $globals['_GET'];
        }
        if ($method === 'post' && is_array($globals['_POST'])) {
            return $globals['_POST'];
        }

        $contentType = $server['CONTENT_TYPE'] ?? ($server['HTTP_CONTENT_TYPE']) ?? '';
        $isFormUrlEncoded = strpos($contentType, 'application/x-www-form-urlencoding') !== false;
        $rawBody = $server['HTTP_RAW_POST_DATA'] ?? rtrim((string) file_get_contents('php://input'));
        if ($isFormUrlEncoded) {
            parse_str(rtrim($rawBody), $put);
            return $put;
        }
        $isApplicationJson = strpos($contentType, 'application/json') !== false;
        if (!$isApplicationJson) {
            return [];
        }
        $content = json_decode($rawBody, true);
        $error = json_last_error();
        if ($error !== JSON_ERROR_NONE) {
            throw new BadRequestJsonException(json_last_error_msg());
        }
        return $content;
    }
}
