<?php
namespace Nora\Framework\Kernel;

use Nora\Framework\DI\Configuration\AbstractConfigurator;
use Nora\Framework\Kernel\Annotation\KernelName;
use Nora\Framework\Kernel\Annotation\ProjectRoot;
use Nora\Framework\Kernel\Provide\Vars\DotEnv\EnvLoader;

class KernelModule extends AbstractConfigurator
{
    public function __construct(KernelMeta $meta)
    {
        $this->meta = $meta;
        parent::__construct(null);
    }

    public function configure()
    {
        $this->bind(KernelMeta::class)->toInstance($this->meta);
        $this
            ->bind(KernelInterface::class)
            ->to($this->meta->name . "\\Kernel\\Kernel");
        $this
            ->bind()
            ->annotatedWith(KernelName::class)
            ->toInstance($this->meta->name);
    }
}
