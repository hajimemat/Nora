<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel;

use Nora\DI\Annotation\Inject;
use Nora\Kernel\Context\Context;
use Nora\Kernel\Extension\Transfer\TransferInterface;
use Nora\Kernel\Helper\ContextHelper;
use Nora\Kernel\Helper\ErrorHandlingHelper;
use Nora\Kernel\Helper\ResourceHelper;
use Nora\Kernel\Helper\RouterHelper;

class Kernel implements KernelInterface
{
    use ContextHelper;
    use RouterHelper;
    use ResourceHelper;
    use ErrorHandlingHelper;

    /**
     * @var TransferInterface
     */
    public $respounder;

    /**
     * @Nora\DI\Annotation\Inject
     */
    public function setRespounder(TransferInterface $respounder)
    {
        $this->respounder = $respounder;
    }
}
