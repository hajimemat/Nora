<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\DI;

use InvalidArgumentException;
use Nora\DI\Dependency\DependencyFactory;
use Nora\DI\Dependency\DependencyInterface;
use Nora\DI\Dependency\Instance;
use Nora\DI\ValueObject\Name;
use Nora\DI\ValueObject\Untarget;
use Nora\Reflection\Factory\NewReflectionClass;
use Nora\Reflection\Factory\NewReflectionMethod;
use ReflectionClass;

class Bind
{
    /**
     * @var string
     */
    private $name = Name::ANY;

    /**
     * @var string
     */
    private $interface;

    /**
     * @var Container
     */
    private $container;

    /**
     */
    private $untarget;

    public function __construct(Container $container, string $interface)
    {
        $this->container = $container;
        $this->interface = $interface;
        $this->validate = new BindValidation();

        // Check If the class is not abstruct and exists
        $isUntarget = class_exists($interface) &&
            !(new ReflectionClass($interface))->isAbstract() &&
            !$this->isRegisterd($interface);

        if ($isUntarget) {
            $this->untarget = new Untarget($interface);
            return;
        }
        return $this->validate->constructor($interface);
    }

    public function isRegisterd() : bool
    {
        return false;
    }

    public function __toString()
    {
        return $this->interface  . '-' . $this->name;
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

    public function annotatedWith(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function toInstance($instance) : self
    {
        $this->untarget = null;
        $this->bound = new Instance($instance);
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
        $postConstructRef = $postConstruct ? (new NewReflectionMethod)($class, $postConstruct) : null;
        $this->bound = (new DependencyFactory)->newToConstructor(
            (new NewReflectionClass)($class),
            $name,
            $injectionPoints,
            $postConstructRef
        );
        $this->container->add($this);
        return $this;
    }


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
}
