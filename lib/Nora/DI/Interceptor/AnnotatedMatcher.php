<?php
declare(strict_types=1);
namespace Nora\DI\Interceptor;

final class AnnotatedMatcher extends BuiltinMatcher
{
    public $annotation;
    public function __construct(string $matcherName, array $arguments)
    {
        parent::__construct($matcherName, $arguments);
        $this->annotation = $arguments[0];
    }
}
