<?php
/**
 * this file is part of Nora
 *
 * @package Resource
 */
declare(strict_types=1);

namespace Nora\Resource\Exception;

use Exception;
use InvalidArgumentException;

class ResourceNotFoundException extends BadRequestException
{
    public function __construct($message = '', $code = HttpStatusCode::NOT_FOUND, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
