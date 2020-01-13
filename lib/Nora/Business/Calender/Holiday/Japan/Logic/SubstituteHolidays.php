<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;
use Nora\Business\Calender\ValueObject\SubstituteHoliday;

/**
 * Should run end of holidays
 */
class SubstituteHolidays
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        $holidays = array_map(function ($v) {
            return $v->getDate();
        }, $provider->getHolidays());
        foreach ($provider->getHolidays() as $holiday) {
            $date = $holiday->getDate();
            if (0 === (int) date('w', strtotime($date))) {
                if ($year >= 2007) {
                    while (in_array($date, $holidays, false)) {
                        $date = date('Y-m-d', strtotime($date.' +1 day'));
                    }
                } elseif ($date >= '1973-04-12') {
                    if (in_array($date, $holidays, false)) {
                        $date = date('Y-m-d', strtotime($date.' +1 day'));
                    }
                } else {
                    continue;
                }
                $provider->getContainer($date)->addHoliday(
                    new SubstituteHoliday(
                        $holiday->getHoliday(),
                        ['ja' => '振替休日']
                    )
                );
            }
        }
    }
}
