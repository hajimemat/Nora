<?php
namespace Nora\Framework\AOP\Pointcut;

class Pointcut
{
    /**
     * @var AbstractMatcher
     */
    public $classMatcher;
    /**
     * @var AbstractMatcher
     */
    public $methodMatcher;
    /**
     * @var array
     */
    public $interceptors;

    public function __construct(
        AbstractMatcher $classMatcher,
        AbstractMatcher $methodMatcher,
        array $interceptors
    ) {
        $this->classMatcher = $classMatcher;
        $this->methodMatcher = $methodMatcher;
        $this->interceptors = $interceptors;
    }
}
