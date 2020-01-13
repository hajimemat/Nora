<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class EnthronementProclamationCeremony
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        if ($year === 2019) {
            $provider->getContainer("2019-10-22")->addHoliday(
                new Holiday(
                    'coronationDay',
                    [
                        'ja' => '即位礼正殿の儀'
                    ]
                )
            );
        }
    }
}
