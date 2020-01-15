<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Framework\Kernel;

use NoraFake\FakeComponent;
use Nora\Framework\AOP\Compiler\Compiler;
use Nora\Framework\DI\Annotation\Inject;
use Nora\Framework\DI\Bind;
use Nora\Framework\DI\Compiler\ScriptInjector;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\DI\Configuration\NullConfigurator;
use Nora\Framework\DI\Dependency\Dependency;
use Nora\Framework\DI\Exception\Untargeted;
use Nora\Framework\DI\Injector;
use Nora\Framework\DI\InjectorInterface;
use Nora\Framework\DI\ValueObject\Name;
use Nora\Framework\Kernel\Exception\InvalidContextException;
use Nora\System\FileSystem\CreateWritableDirectory;

class KernelInjector implements InjectorInterface
{
    /**
     * @var string
     */
    private $scriptDir;

    /**
     * @var InjectorInterface
     */
    private $injector;

    /**
     * @var Configurator
     */
    private $configurator;

    /**
     * @var KernelMeta
     */
    private $meta;

    /**
     * @var array
     */
    public $loadedContexts;

    /**
     * Setup Kernel Injector
     */
    public function __construct(KernelMeta $meta)
    {
        $this->meta = $meta;
        $this->scriptDir = $meta->tmpDir . '/di';
        $this->classDir = (new CreateWritableDirectory)($meta->tmpDir . '/class');
        // $this->injector = new ScriptInjector($this->scriptDir, function () {
        //     return $this->getConfigurator();
        // });
        // $this->injector = new Injector($this->getConfigurator());
        $this->container = $this->getConfigurator()->getContainer();
        $this->container->weaveAspects(new Compiler($this->classDir));
    }

    /**
     * {@inheritDoc}
     */
    public function getInstance($interface, $name = Name::ANY)
    {
        try {
            $instance = $this->container->getInstance($interface, $name);
        } catch (Untargeted $e) {
            $this->bind($interface);
            $instance = $this->getInstance($interface, $name);
        }
        return $instance;
    }

    private function bind(string $class)
    {
        echo "\n";
        vaR_Dump($class."TTTT");
        // vaR_Dump(FakeComponent::class);
        (new Bind($this->container, $class));
        $this->container->getInstance($class, Name::ANY);
        // $bound = $this->container[$class . '-' . Name::ANY];
        // if ($bound instanceof Dependency) {
        //     $this->container->weaveAspect(
        //         new Compiler($this->classDir),
        //         $bound
        //     )->getInstance($class, Name::ANY);
        // }
    }

    /**
     * Configuration
     */
    private function getConfigurator()
    {
        if ($this->configurator instanceof AbstractConfigurator) {
            return $this->configurator;
        }
        $contextsArray = array_reverse(explode('-', $this->meta->context));
        $configurator = new NullConfigurator;

        // Context
        $this->loadedContexts = [];
        foreach ($contextsArray as $contextItem) {
            $class = $this->meta->name . '\Kernel\Context\\' . ucwords($contextItem) . 'Configurator';
            if (! class_exists($class)) {
                $class = 'Nora\Framework\Kernel\Context\\' . ucwords($contextItem) . 'Context';
            }
            if (! is_a($class, AbstractConfigurator::class, true)) {
                throw new InvalidContextException($contextItem);
            }
            $this->loadedContexts[$contextItem] = $class;

            $configurator = is_subclass_of(
                $class,
                AbstractKernelConfigurator::class
            ) ? new $class($this->meta, $configurator) : new $class($configurator);
        }
        if (! $configurator instanceof AbstractConfigurator) {
            throw new InvalidModuleException; // @codeCoverageIgnore
        }

        $configurator->override(new KernelModule($this->meta));

        // Bind
        (new Bind($configurator->getContainer(), InjectorInterface::class))->toInstance($this);

        $this->configurator = $configurator;
        return $configurator;
    }
}
