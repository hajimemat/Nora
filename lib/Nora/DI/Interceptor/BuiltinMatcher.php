<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\Interceptor;

use ReflectionClass;

class BuiltinMatcher extends AbstractMatcher
{
    /**
     * @var string
     */
    private $matcherName;

    public function __construct(string $matcherName, array $arguments)
    {
        parent::__construct();
        $this->matcherName = $matcherName;
        $this->arguments = $arguments;
        $matcherClass = __NAMESPACE__.'\\Matcher\\'.ucwords($this->matcherName).'Matcher';
        $matcher = (new ReflectionClass($matcherClass))->newInstance();
        if (!$matcher instanceof AbstractMatcher) {
            throw new InvalidMatcherException($matcherClass);
        }
        $this->matcher = $matcher;
    }

    /**
     * {@inheritdoc}
     */
    public function matchesClass(\ReflectionClass $class, array $arguments) : bool
    {
        return $this->matcher->matchesClass($class, $arguments);
    }
    /**
     * {@inheritdoc}
     */
    public function matchesMethod(\ReflectionMethod $method, array $arguments) : bool
    {
        return $this->matcher->matchesMethod($method, $arguments);
    }
}
