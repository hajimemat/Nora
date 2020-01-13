<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class GreeneryDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year >= 2007) {
            $date = "$year-05-04";
        } elseif ($year >= 1989) {
            $date = "$year-04-29";
        }
        if (isset($date)) {
            $provider->getContainer($date)->addHoliday(
                new Holiday(
                    'greeneryDay',
                    [
                        'ja' => 'みどりの日'
                    ]
                )
            );
        }
    }
}
