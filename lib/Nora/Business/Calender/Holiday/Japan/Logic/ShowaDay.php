<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class ShowaDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year >= 2007) {
            $provider->getContainer("{$year}-04-29")->addHoliday(
                new Holiday(
                    'showaDay',
                    [
                        'ja' => '昭和の日'
                    ]
                )
            );
        }
    }
}
