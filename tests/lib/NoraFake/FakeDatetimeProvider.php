<?php
namespace NoraFake;

use Nora\Business\Calender\DateTime\DateTimeFactory;
use Nora\Framework\DI\Dependency\ProviderInterface;

class FakeDatetimeProvider implements ProviderInterface
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Tokyo');
        $this->factory = new DateTimeFactory();
    }

    public function get()
    {
        return ($this->factory)(FakeDatetime::class);
    }
}
