<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class RespectForTheAgeDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year >= 2003) {
            $date = date('Y-m-d', strtotime("third monday of september {$year}"));
        } elseif ($year >= 1996) {
            $date = "{$year}-09-15";
        }

        if (isset($date)) {
            $provider->getContainer($date)->addHoliday(
                new Holiday(
                    'respectForTheAgeDay',
                    [
                        'ja' => '敬老の日'
                    ]
                )
            );
        }
    }
}
