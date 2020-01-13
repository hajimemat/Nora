<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class ComingOfAgeDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year >= 2000) {
            $date = date('Y-m-d', strtotime("second monday of january $year"));
        } elseif ($year >= 1948) {
            $date = "{$year}-01-15";
        }
        if (isset($date)) {
            $provider->getContainer($date)->addHoliday(
                new Holiday(
                    'comingOfAgeDay',
                    [
                        'ja' => '成人の日'
                    ]
                )
            );
        }
    }
}
