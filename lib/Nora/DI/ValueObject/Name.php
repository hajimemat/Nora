<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\ValueObject;

use InvalidArgumentException;
use ReflectionParameter;

final class Name
{
    const ANY = "";

    /**
     * @var string $name
     */
    private $name = null;

    /**
     * @var string[] $names
     */
    private $names = [];

    public function __construct(string $name = null)
    {
        if (class_exists($name, false)) {
            $this->name = $name;
        } elseif ($name === self::ANY || preg_match('/^\w+$/', $name)) {
            $this->name = $name;
        }

        $keyValues = explode(",", $name);
        array_walk($keyValues, function ($v) {
            $key = strtok($v, "=");
            if (isset($key[0]) && $key[0] === '$') {
                $key = substr($key, 1);
            }
            if ($value = strtok("")) {
                $this->names[trim($key)] = trim($value);
            }
        });
    }

    public function __invoke(ReflectionParameter $parameter) : string
    {
        if ($this->name) {
            return $this->name;
        }

        if (isset($this->names[$parameter->name])) {
            return $this->names[$parameter->name];
        }

        if (isset($this->names[self::ANY])) {
            return $this->names[self::ANY];
        }

        return self::ANY;
    }
}
