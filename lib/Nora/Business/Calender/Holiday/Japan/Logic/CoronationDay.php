<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class CoronationDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year === 2019) {
            $provider->getContainer("2019-05-01")->addHoliday(
                new Holiday(
                    'coronationDay',
                    [
                        'ja' => '即位の日'
                    ]
                )
            );
        }
    }
}
