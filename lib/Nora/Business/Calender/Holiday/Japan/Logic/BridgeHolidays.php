<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use DateInterval;
use DateTime;
use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;
use Nora\Business\Calender\ValueObject\SubstituteHoliday;

/**
 * Should run end of holidays
 */
class BridgeHolidays
{
    public function __invoke(ProviderInterface $provider, int $year)
    {
        $holidays = array_map(function ($v) {
            return $v->getDate();
        }, $provider->getHolidays());

        asort($holidays);
        $holidays = array_values($holidays);

        $cur = new DateTime(current($holidays));
        for ($i=1; $i<count($holidays); $i++) {
            $prev = $cur;
            $cur = new DateTime($holidays[$i]);
            if (2 === (int)$cur->diff($prev)->format('%a')) {
                $bridgeDate = $prev->add(new DateInterval('P1D'))->format('Y-m-d');
                $provider->getContainer($bridgeDate)->addHoliday(
                    new Holiday(
                        'bridgeDay',
                        [
                            'ja' => '国民の休日'
                        ]
                    )
                );
                continue;
            }
        }
    }
}
