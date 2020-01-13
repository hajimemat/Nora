<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class LaborThanksgivingDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year >= 1948) {
            $provider->getContainer("{$year}-11-23")->addHoliday(
                new Holiday(
                    'laborThanksgivingDay',
                    [
                        'ja' => '勤労感謝の日'
                    ]
                )
            );
        }
    }
}
