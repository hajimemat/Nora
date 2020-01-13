<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class NationalFundationDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year >= 1966) { // 1966年
            $provider->getContainer("{$year}-02-11")->addHoliday(
                new Holiday(
                    'nationalFoundationDay',
                    [
                        'ja' => '建国記念の日'
                    ]
                )
            );
        }
    }
}
