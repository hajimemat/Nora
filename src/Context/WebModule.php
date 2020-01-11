<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace NoraApp\Context;

use Nora\DI\Module\AbstractModule;

class WebModule extends AbstractModule
{
    public function configure()
    {
        $this->install(new \Nora\Kernel\Context\WebModule($this));

        $this
            ->bind()
            ->annotatedWith('template_dirs')
            ->toInstance([
                dirname(__DIR__, 2).'/views'
            ]);
    }
}
