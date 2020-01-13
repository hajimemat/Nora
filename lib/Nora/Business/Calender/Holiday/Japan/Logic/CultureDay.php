<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class CultureDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year >= 1948) {
            $provider->getContainer("{$year}-11-03")->addHoliday(
                new Holiday(
                    'cultureDay',
                    [
                        'ja' => '文化の日'
                    ]
                )
            );
        }
    }
}
