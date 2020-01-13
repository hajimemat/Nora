<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace NoraFake;

class FakeTraceClient
{
    /**
     * @FakeTrace
     */
    public function intercepted()
    {
        return 'aaa {name} bbb';
    }
}
