<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\DI;

use Nora\DI\Module\AbstractModule;
use Nora\DI\Module\NullModule;
use Nora\Kernel\Context\Context;
use Nora\Kernel\Exception\InvalidContextException;

final class NewModule
{
    public function __invoke(Context $context) : AbstractModule
    {
        $contexts = $context->getContextStringArray();
        $revContexts = array_reverse($contexts);

        $module = new NullModule();
        foreach ($revContexts as $contextItem) {
            $class = $context->namespaced('Context', ucwords($contextItem)."Module");
            if (!class_exists($class)) {
                $class = "Nora\\Kernel\\Context\\".ucwords($contextItem)."Module";
            }
            if (!is_a($class, AbstractModule::class, true)) {
                throw new InvalidContextException("\"{$contextItem}\" is not a valid context");
            }
            $module = new $class($module);
        }

        return $module;
    }
}
