<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class MarineDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year == 2020) {
            $date = "{$year}-07-23";
        } elseif ($year >= 2003) {
            $date = date('Y-m-d', strtotime("third monday of july {$year}"));
        } elseif ($year >= 1996) {
            $date = "{$year}-07-20";
        }

        if (isset($date)) {
            $provider->getContainer($date)->addHoliday(
                new Holiday(
                    'marineDay',
                    [
                        'ja' => '海の日'
                    ]
                )
            );
        }
    }
}
