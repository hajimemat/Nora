<?php
/**
 * this file is part of Nora
 *
 * @package Resource
 */
declare(strict_types=1);

namespace Nora\Resource\Exception;

use BadMethodCallException;
use Exception;
use InvalidArgumentException;

class BadRequestException extends BadMethodCallException implements ExceptionInterface
{
    public function __construct($message = '', $code = HttpStatusCode::BAD_REQUEST, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
