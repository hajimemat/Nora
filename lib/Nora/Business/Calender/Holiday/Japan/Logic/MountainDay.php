<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class MountainDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year == 2020) {
            $date = "{$year}-08-10";
        } elseif ($year >= 2016) {
            $date = "{$year}-08-11";
        }

        if (isset($date)) {
            $provider->getContainer($date)->addHoliday(
                new Holiday(
                    'marineDay',
                    [
                        'ja' => '山の日'
                    ]
                )
            );
        }
    }
}
