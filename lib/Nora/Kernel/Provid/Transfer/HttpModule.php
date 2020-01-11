<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Provid\Transfer;

use Nora\DI\Module\AbstractModule;
use Nora\Kernel\Extension\Transfer\TransferInterface;

class HttpModule extends AbstractModule
{
    public function configure()
    {
        $this->bind(TransferInterface::class)->to(HttpResponder::class);
    }
}
