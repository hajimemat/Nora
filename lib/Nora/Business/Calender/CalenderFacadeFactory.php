<?php
namespace Nora\Business\Calender;

use DateTime as OriginalDateTime;
use DateTimeZone;
use Nora\Business\Calender\Constant\TimeZone;
use Nora\Business\Calender\Holiday\HolidayProviderFactory;

class CalenderFacadeFactory
{
    public function __invoke(DateTimeZone $tz) : CalenderFacade
    {
        // 法定休日プロバイダを作成する
        $name = TimeZone::getCountryName($tz->getName());

        return new CalenderFacade(
            (new HolidayProviderFactory)($name)
        );
    }
}
