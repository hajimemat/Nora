<?php
namespace Nora\Business\Calender\Holiday\Japan\Logic;

use Nora\Business\Calender\Holiday\HolidayProviderInterface as ProviderInterface;
use Nora\Business\Calender\ValueObject\Holiday;

class AutumnalEquinoxDay
{
    /**
     * The gradient parameter of the approximate expression to calculate equinox day.
     */
    private const EQUINOX_GRADIENT = 0.242194;
    /**
     * The initial parameter of the approximate expression to calculate autumnal equinox day from 1900 to 1979.
     */
    private const AUTUMNAL_EQUINOX_PARAM_1979 = 23.2588;
    /**
     * The initial parameter of the approximate expression to calculate autumnal equinox day from 1980 to 2099.
     */
    private const AUTUMNAL_EQUINOX_PARAM_2099 = 23.2488;
    /**
     * The initial parameter of the approximate expression to calculate autumnal equinox day from 2100 to 2150.
     */
    private const AUTUMNAL_EQUINOX_PARAM_2150 = 24.2488;


    public function __invoke(ProviderInterface $provider, int $year)
    {
        $day = null;
        if ($year >= 1948 && $year <= 1979) {
            $day = floor(self::AUTUMNAL_EQUINOX_PARAM_1979 + self::EQUINOX_GRADIENT * ($year - 1980) - floor(($year - 1983) / 4));
        } elseif ($year <= 2099) {
            $day = floor(self::AUTUMNAL_EQUINOX_PARAM_2099 + self::EQUINOX_GRADIENT * ($year - 1980) - floor(($year - 1980) / 4));
        } elseif ($year <= 2150) {
            $day = floor(self::AUTUMNAL_EQUINOX_PARAM_2150 + self::EQUINOX_GRADIENT * ($year - 1980) - floor(($year - 1980) / 4));
        }
        if ($year < 1948 || $year > 2150) {
            $day = null;
        }

        if (is_numeric($day)) {
            $provider->getContainer(sprintf("{$year}-09-%02d", $day))->addHoliday(
                new Holiday(
                    'autumnalEquinoxDay',
                    ['en' => 'Autumnal Equinox Day', 'ja' => '秋分の日']
                )
            );
        }
    }
}
