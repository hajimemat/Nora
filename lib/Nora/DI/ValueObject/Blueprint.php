<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\ValueObject;

use Doctrine\Common\Annotations\AnnotationReader;
use InvalidArgumentException;
use Nora\DI\Annotation\Named;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

final class Blueprint
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    public function getNewInstance(ReflectionClass $class) : NewInstance
    {
        foreach ($class->getMethods() as $method) {
            if ($method->name === '__construct') {
                continue;
            }
        }

        $name = $this->getConstructorName($class);

        $setterMethods = new SetterMethods([]);

        return new NewInstance($class, $setterMethods, $name);
    }

    public function getPostConstruct(ReflectionClass $class) : ?ReflectionMethod
    {
        foreach ($class->getMethods() as $method) {
            if ($method->name === 'postConstruct') {
                return $method;
            }
        }
        return null;
    }

    /**
     * @see https://github.com/ray-di/Ray.Di/blob/dd17568be44168c6f3649b247d44dc3c59d64ab5/src/AnnotatedClassMethods.php#L24
     */
    private function getConstructorName(ReflectionClass $class) : Name
    {
        $constructor = $class->getConstructor();
        if (!$constructor) {
            return new Name(Name::ANY);
        }

        // $named = $this->reader->getMethodAnnotation($constructor, Named::class);
        $named = $this->reader->getMethodAnnotation($constructor, Named::class);
        if ($named instanceof Named) {
            return new Name($named->value);
        }
        
        return new Name(Name::ANY);
    }
}
