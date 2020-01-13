<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class SportsDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year == 2020) {
            $date = "{$year}-07-24";
        } elseif ($year >= 2000) {
            $date = date('Y-m-d', strtotime("second monday of october {$year}"));
        } elseif ($year >= 1996) {
            $date = "{$year}-10-10";
        }

        if (isset($date)) {
            $provider->getContainer($date)->addHoliday(
                new Holiday(
                    'sportsDay',
                    [
                        'ja' => ($year >= 2020) ? 'スポーツの日': '体育の日'
                    ]
                )
            );
        }
    }
}
