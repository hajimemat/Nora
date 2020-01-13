<?php
namespace Nora\Business\Calender\Constant;

final class TimeZone
{
    public static $timezone = [
        'Asia/Tokyo' => 'Japan'
    ];

    public static function getCountryName($timezone) : string
    {
        return self::$timezone[$timezone];
    }
}
