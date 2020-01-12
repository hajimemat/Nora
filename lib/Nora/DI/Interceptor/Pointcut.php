<?php
namespace Nora\DI\Interceptor;

use Nora\DI\Bind;
use Nora\DI\Container;
use Nora\DI\Interceptor\AbstractMatcher;
use Nora\DI\Interceptor\Matcher;

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
