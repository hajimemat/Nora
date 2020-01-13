<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class ConstitutionMemorialDay
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year >= 1948) {
            $provider->getContainer("{$year}-05-03")->addHoliday(
                new Holiday(
                    'constitutionMemorialDay',
                    [
                        'ja' => '憲法記念日'
                    ]
                )
            );
        }
    }
}
