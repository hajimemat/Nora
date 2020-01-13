<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Dependency;

use Nora\Framework\DI\Bind;
use Nora\Framework\DI\Container\ContainerInterface;
use Nora\Framework\DI\ValueObject\Scope;
use ReflectionClass;

final class CreateUntargetBinding
{
    /**
     * @var string Class Name
     */
    private $class;

    /**
     * @var string Scope
     */
    private $scope = Scope::PROTOTYPE;

    public function __construct(string $class)
    {
        $this->class = new ReflectionClass($class);
    }

    public function __invoke(ContainerInterface $container, Bind $bind)
    {
        $bound = (new DependencyFactory)->newDependency($this->class);
        $bound->setScope($this->scope);
        $bind->setBound($bound);
        $container->add($bind);
    }
    //     $bound = (new DependencyFactory)->newDependency($this->class);
    //     $bound->setScope($this->scope);
    //     $bind->setBound($bound);
    //     $container->add($bind);
    //     $constructor = $this->class->getConstructor();
    //     if ($constructor) {
    //         $parameters = $constructor->getParameters();
    //         foreach ($parameters as $parameter) {
    //             $typeHint = $parameter->getClass();
    //             if ($typeHint) {
    //                 new Bind($container, $typeHint->name);
    //             }
    //         }
    //     }
    // }
    //
    public function setScope(string $scope)
    {
        $this->scope = $scope;
    }
}
