<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI\ValueObject;

use InvalidArgumentException;
use Nora\DI\Bind;
use Nora\DI\Constant\Scope;
use Nora\DI\Container;
use Nora\DI\Dependency\DependencyFactory;
use Nora\Reflection\Factory\NewReflectionClass;
use ReflectionParameter;

final class Untarget
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $scope = Scope::PROTOTYPE;

    public function __construct(string $class)
    {
        $this->class = (new NewReflectionClass)($class);
    }

    public function __invoke(Container $container, Bind $bind)
    {
        $bound = (new DependencyFactory)->newDependency($this->class);
        $bound->setScope($this->scope);
        $bind->setBound($bound);
        $container->add($bind);
        $constructor = $this->class->getConstructor();
        if ($constructor) {
            $parameters = $constructor->getParameters();
            foreach ($parameters as $parameter) {
                $typeHint = $parameter->getClass();
                if ($typeHint) {
                    new Bind($container, $typeHint->name);
                }
            }
        }
    }

    public function setScope(string $scope)
    {
        $this->scope = $scope;
    }
}
