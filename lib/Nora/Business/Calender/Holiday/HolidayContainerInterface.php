<?php
namespace Nora\Business\Calender\Holiday;

use Nora\Business\Calender\ValueObject\Holiday;

interface HolidayContainerInterface
{
    public function addHoliday(Holiday $holiday);
}
