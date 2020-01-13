<?php
declare(strict_types=1);
namespace Nora\Framework\AOP\Pointcut;

use Nora\Framework\AOP\Exception\InvalidMatcherException;

class BuiltinMatcher extends AbstractMatcher
{
    /**
     * @var string
     */
    private $matcherName;
    /**
     * @var AbstractMatcher
     */
    private $matcher;
    /**
     * @throws \ReflectionException
     */
    public function __construct(string $matcherName, array $arguments)
    {
        parent::__construct();
        $this->matcherName = $matcherName;
        $this->arguments = $arguments;
        $matcherClass = 'Nora\Framework\AOP\Pointcut\Matcher\\' . ucwords($this->matcherName) . 'Matcher';
        $matcher = (new \ReflectionClass($matcherClass))->newInstance();
        if (! $matcher instanceof AbstractMatcher) {
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
