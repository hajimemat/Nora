<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\Interceptor;

abstract class AbstractMatcher
{
    /**
     * @var array
     */
    protected $arguments = [];

    public function __construct()
    {
        $this->arguments = func_get_args();
    }

    /**
     * Match class condition
     *
     * @return bool
     */
    abstract public function matchesClass(\ReflectionClass $class, array $arguments);

    /**
     * Match method condition
     *
     * @return bool
     */
    abstract public function matchesMethod(\ReflectionMethod $method, array $arguments);

    /**
     * Return matching condition arguments
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
