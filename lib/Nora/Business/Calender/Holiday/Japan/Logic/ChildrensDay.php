<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class ChildrensDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year >= 1948) {
            $provider->getContainer("{$year}-05-05")->addHoliday(
                new Holiday(
                    'childrenDay',
                    [
                        'ja' => 'こどもの日'
                    ]
                )
            );
        }
    }
}
