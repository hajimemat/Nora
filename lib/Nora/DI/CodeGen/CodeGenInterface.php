<?php
declare(strict_types=1);
namespace Nora\DI\CodeGen;

use Nora\DI\Aop\BindInterface;
use ReflectionClass;

interface CodeGenInterface
{
    public function generate(ReflectionClass $sourceClass, BindInterface $bind) : Code;
}
