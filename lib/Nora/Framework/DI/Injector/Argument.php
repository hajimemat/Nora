<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Injector;

use ReflectionException;
use ReflectionParameter;
use ReflectionType;

final class Argument
{
    /**
     * @var string
     */
    private $index;

    /**
     * @var mixed
     */
    private $default = null;

    /**
     * @var bool
     */
    private $isDefaultAvailable;

    /**
     * @var string
     */
    private $meta;

    /**
     * @var ReflectionParameter
     */
    private $reflection;

    public function __construct(ReflectionParameter $reflection, string $name)
    {
        // Get ClassName or Empty if normal type
        $reflectionType = $reflection->getType();
        $type = '';
        if ($reflectionType instanceof ReflectionType) {
            $isStandard = in_array(
                $reflectionType->getName(),
                ['bool', 'int', 'string', 'array', 'resource', 'callable'],
                true
            );

            if (!$isStandard) {
                $type = $reflectionType->getName();
            }
        }

        $isOptional = $reflection->isOptional();
        $this->isDefaultAvailable = $reflection->isDefaultValueAvailable() || $isOptional;
        if ($isOptional) {
            $this->default = null;
        }
        if ($this->isDefaultAvailable) {
            try {
                $this->default = $reflection->getDefaultValue();
            } catch (ReflectionException $e) {
                $this->default = null;
            }
        }
        $this->reflection = $reflection;
        $this->index = $type.'-'.$name;

        $this->meta = sprintf(
            "dependency '%s' with name '%s' used in %s:%d ($%s)",
            $type,
            $name,
            $this->reflection->getDeclaringFunction()->getFileName(),
            $this->reflection->getDeclaringFunction()->getStartLine(),
            $this->reflection->getName()
        );
    }

    public function __toString()
    {
        return $this->index;
    }

    public function isDefaultAvailable() : bool
    {
        return $this->isDefaultAvailable;
    }

    public function getDefaultValue()
    {
        return $this->default;
    }

    public function getMeta() : string
    {
        return $this->meta;
    }

    public function serialize() : string
    {
        $method = $this->reflection->getDeclaringFunction();
        $ref = [
            $method->class,
            $method->name,
            $this->reflection->getName()
        ];
        return serialize([
            $this->index,
            $this->isDefaultAvailable,
            $this->default,
            $this->meta,
            $ref
        ]);
    }

    public function unserialize(string $serialized)
    {
        list(
            $this->index,
            $this->isDefaultAvailable,
            $this->default,
            $this->meta,
            $ref
        ) = unserialize($serialized);

        $this->reflection = new ReflectionParameter($ref[0], $ref[1], $ref[2]);
    }
}
