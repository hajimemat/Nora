<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class EmperorsBirthday
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year >= 2020) {
            $provider->getContainer("{$year}-02-23")->addHoliday(
                new Holiday(
                    'EmperorsBirthday',
                    [
                        'ja' => '天皇誕生日'
                    ]
                )
            );
        }

        if ($year >= 1989 && $year < 2019) {
            $provider->getContainer("{$year}-12-23")->addHoliday(
                new Holiday(
                    'EmperorsBirthday',
                    [
                        'ja' => '天皇誕生日'
                    ]
                )
            );
        }

        if ($year >= 1949 && $year < 1988) {
            $provider->getContainer("{$year}-04-29")->addHoliday(
                new Holiday(
                    'EmperorsBirthday',
                    [
                        'ja' => '天皇誕生日'
                    ]
                )
            );
        }
    }
}
