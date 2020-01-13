<?php
declare(strict_types=1);
namespace Nora\Framework\AOP\Compiler\CodeGen;

use Nora\Framework\AOP\Bind\BindInterface;
use ReflectionClass;

interface CodeGenInterface
{
    public function generate(ReflectionClass $sourceClass, BindInterface $bind) : Code;
}
