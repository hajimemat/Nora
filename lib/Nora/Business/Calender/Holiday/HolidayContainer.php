<?php
namespace Nora\Business\Calender\Holiday;

use Nora\Business\Calender\ValueObject\HolidayInterface;

class HolidayContainer implements HolidayContainerInterface
{
    public $holidays = [];
    private $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function addHoliday(HolidayInterface $holiday)
    {
        $this->holidays[] = $holiday;
    }


    public function __toString()
    {
        return sprintf(
            "(date info) %s %s",
            $this->date,
            implode(",", $this->holidays)
        );
    }

    public function getDate()
    {
        return $this->date;
    }

    public function hasHoliday() : bool
    {
        return empty($this->holidays) ? false: true;
    }

    public function getHoliday()
    {
        return current($this->holidays);
    }
}
