<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Manipulation;

use Doctrine\Common\Annotations\AnnotationReader;
use InvalidArgumentException;
use Nora\Framework\Annotation\AnnotationHelper;
use Nora\Framework\DI\Annotation\InjectInterface;
use Nora\Framework\DI\Annotation\Named;
use Nora\Framework\DI\Annotation\PostConstruct;
use Nora\Framework\DI\Injector\Instantiator;
use Nora\Framework\DI\Injector\MethodInjector;
use Nora\Framework\DI\Injector\MethodInjectors;
use Nora\Framework\DI\ValueObject\Name;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

final class ClassManipulator
{
    /**
     * @var AnnotationReader
     */
    private $reader;
    private $class;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    public function __invoke(ReflectionClass $class)
    {
        $this->class = $class;
        return $this;
    }

    public function buildInstantiator() : Instantiator
    {
        $class = $this->class;

        $methodInjectors = [];
        foreach ($class->getMethods() as $method) {
            if ($method->name === '__construct') {
                continue;
            }
            $annot = (new AnnotationHelper($this->reader))->methodAnnotation($method);
            if (($inject = $annot(InjectInterface::class)) === false) {
                continue;
            }
            $nameValue = ($named = $annot(Named::class)) === false? Name::ANY: $named->value;
            $methodInjector = new MethodInjector(
                $method,
                new Name($nameValue)
            );

            if ($inject->isOptional()) {
                $methodInjector->setOptional();
            }
            $methodInjectors[] = $methodInjector;
        }
        $name = $this->getConstructorName($class);

        return new Instantiator($class, new MethodInjectors($methodInjectors), $name);
    }

    public function getPostConstruct(ReflectionClass $class) : ?ReflectionMethod
    {
        foreach ($class->getMethods() as $method) {
            $annot = (new AnnotationHelper($this->reader))->methodAnnotation($method);
            if ($annot(PostConstruct::class) !== false) {
                return $method;
            }
        }
        return null;
    }

    private function getConstructorName(ReflectionClass $class) : Name
    {
        $constructor = $class->getConstructor();
        if (!$constructor) {
            return new Name(Name::ANY);
        }

        $named = $this->reader->getMethodAnnotation($constructor, Named::class);
        if ($named instanceof Named) {
            return new Name($named->value);
        }

        $name = $this->getNamedKeyVarString($constructor);
        if ($name !== null) {
            return new Name($name);
        }

        return new Name(Name::ANY);
    }

    /**
     * @return null|string
     */
    private function getNamedKeyVarString(\ReflectionMethod $method)
    {
        $keyVal = [];
        $named = $this->reader->getMethodAnnotation($method, Named::class);
        if ($named instanceof Named) {
            $keyVal[] = $named->value;
        }
        $qualifierNamed = $this->getQualifierKeyVarString($method);
        if ($qualifierNamed) {
            $keyVal[] = $qualifierNamed;
        }
        if ($keyVal !== []) {
            return implode(',', $keyVal); // var1=qualifier1,va2=qualifier2
        }
    }
    private function getQualifierKeyVarString(\ReflectionMethod $method) : string
    {
        $annotations = $this->reader->getMethodAnnotations($method);
        $names = [];
        foreach ($annotations as $annotation) {
            /* @var $bindAnnotation object|null */
            $qualifier = $this->reader->getClassAnnotation(new \ReflectionClass($annotation), Qualifier::class);
            if ($qualifier instanceof Qualifier) {
                $value = $annotation->value ?? Name::ANY;
                $names[] = sprintf('%s=%s', $value, \get_class($annotation));
            }
        }
        return implode(',', $names);
    }
}
