<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\Dotenv;

/**
 * Dotenv Factory
 */
class NewDotenv
{
    public function __invoke(string $path) : Dotenv
    {
        return new Dotenv($path);
    }
}
