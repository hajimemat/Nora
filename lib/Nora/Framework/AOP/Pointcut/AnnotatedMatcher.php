<?php
declare(strict_types=1);
namespace Nora\Framework\AOP\Pointcut;

final class AnnotatedMatcher extends BuiltinMatcher
{
    public $annotation;
    public function __construct(string $matcherName, array $arguments)
    {
        parent::__construct($matcherName, $arguments);
        $this->annotation = $arguments[0];
    }
}
