<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Framework\Kernel;

use Nora\Framework\DI\Annotation\Inject;
use Nora\Framework\DI\Compiler\ScriptInjector;
use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\DI\Configuration\NullConfigurator;
use Nora\Framework\DI\InjectorInterface;

class KernelMeta
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $context;
    /**
     * @var string
     */
    public $directory;
    /**
     * @var string
     */
    public $tmpDir;
    /**
     * @var string
     */
    public $logDir;

    public function __construct(string $name, string $context, string $directory)
    {
        $this->name = $name;
        $this->context = $context;
        $this->directory = $directory;
    }
}
