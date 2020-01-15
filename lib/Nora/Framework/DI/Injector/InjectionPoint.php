<?php
declare(strict_types=1);
namespace Nora\Framework\DI\Injector;

use Doctrine\Common\Annotations\Reader;
use Nora\Framework\DI\Annotation\Qualifier;

final class InjectionPoint implements InjectionPointInterface
{
    /**
     * @var \ReflectionParameter
     */
    private $parameter;
    /**
     * @var Reader
     */
    private $reader;
    public function __construct(\ReflectionParameter $parameter, Reader $reader)
    {
        $this->parameter = $parameter;
        $this->reader = $reader;
    }
    /**
     * {@inheritdoc}
     */
    public function getParameter() : \ReflectionParameter
    {
        return $this->parameter;
    }
    /**
     * {@inheritdoc}
     */
    public function getMethod() : \ReflectionMethod
    {
        $class = $this->parameter->getDeclaringClass();
        if (! $class instanceof \ReflectionClass) {
            throw new \LogicException($this->parameter->getName());
        }
        $method = $this->parameter->getDeclaringFunction()->getShortName();
        return new \ReflectionMethod($class->name, $method);
    }
    /**
     * {@inheritdoc}
     */
    public function getClass() : \ReflectionClass
    {
        $class = $this->parameter->getDeclaringClass();
        if ($class instanceof \ReflectionClass) {
            return $class;
        }
        throw new \LogicException($this->parameter->getName());
    }
    /**
     * {@inheritdoc}
     */
    public function getQualifiers() : array
    {
        $qualifiers = [];
        $annotations = $this->reader->getMethodAnnotations($this->getMethod());
        foreach ($annotations as $annotation) {
            $qualifier = $this->reader->getClassAnnotation(
                new \ReflectionClass($annotation),
                Qualifier::class
            );
            if ($qualifier instanceof Qualifier) {
                $qualifiers[] = $annotation;
            }
        }
        return $qualifiers;
    }
}
