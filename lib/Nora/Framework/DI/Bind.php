<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI;

use Nora\Framework\DI\Dependency\CreateUntargetBinding;
use Nora\Framework\DI\Dependency\DependencyFactory;
use Nora\Framework\DI\Dependency\DependencyInstance;
use Nora\Framework\DI\Dependency\DependencyInterface;
use Nora\Framework\DI\Container\ContainerInterface;
use Nora\Framework\DI\Validator\BindValidator;
use Nora\Framework\DI\ValueObject\Name;
use ReflectionClass;

class Bind
{
    /**
     * @var DependencyInterface
     */
    private $bound;

    /**
     * @var DependencyInterface
     */
    private $untarget;

    /**
     * @var string
     */
    private $name = Name::ANY;

    public function __construct(ContainerInterface $container, string $interface)
    {
        $this->container = $container;
        $this->interface = $interface;
        $this->validate = new BindValidator();
        //
        // // Check If the class is not abstruct and exists
        $isUntarget = class_exists($interface) &&
            !(new ReflectionClass($interface))->isAbstract() &&
            !$this->isRegisterd($interface);

        if ($isUntarget) {
            $this->untarget = (new CreateUntargetBinding($this->interface));
            return;
        }
        $this->validate->constructor($interface);
    }

    public function isRegisterd(string $interface) : bool
    {
        return isset($this->container[$interface .'-'. Name::ANY]);
    }

    public function __destruct()
    {
        if ($this->untarget) {
            ($this->untarget)($this->container, $this);
            $this->untarget = null;
        }
    }


    public function setBound(DependencyInterface $bound)
    {
        $this->bound = $bound;
    }

    public function getBound() : DependencyInterface
    {
        return $this->bound;
    }

    public function __toString()
    {
        return $this->interface  . '-' . $this->name;
    }

    public function annotatedWith(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function toInstance($instance) : self
    {
        $this->untarget = null;
        $this->bound = new DependencyInstance($instance);
        $this->container->add($this);
        return $this;
    }

    public function to(string $class) : self
    {
        $this->untarget = null;
        $refClass = $this->validate->to($this->interface, $class);
        $this->bound = (new DependencyFactory)->newDependency($refClass);
        $this->container->add($this);
        return $this;
    }


    public function toConstructor(
        string $class,
        $name,
        $injectionPoints = null,
        string $postConstruct = null
    ) : self {
        $this->untarget = null;
        $postConstructRef = $postConstruct ? (new ReflectionClass($class))->getMethod($postConstruct) : null;
        $this->bound = (new DependencyFactory)->newToConstructor(
            (new ReflectionClass($class)),
            $name,
            $injectionPoints,
            $postConstructRef
        );
        $this->container->add($this);
        return $this;
    }
    //
    //
    public function toProvider(
        string $provider,
        string $context = ''
    ) : self {
        $this->untarget = null;
        $refClass = $this->validate->toProvider($provider);
        $this->bound = (new DependencyFactory)->newProvider($refClass, $context);
        $this->container->add($this);
        return $this;
    }

    public function in(string $scope) : self
    {
        if ($this->bound instanceof Dependency || $this->bound instanceof DependencyProvider) {
            $this->bound->setScope($scope);
        }
        if ($this->untarget) {
            $this->untarget->setScope($scope);
        }
        return $this;
    }
}
