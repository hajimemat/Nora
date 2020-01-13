<?php
namespace Nora\Business\Calender\ValueObject;

class SubstituteHoliday implements HolidayInterface
{
    /**
     * @var array
     */
    private $names;

    /**
     * @var Holiday
     */
    private $holiday;

    public function __construct(Holiday $holiday, array $names)
    {
        $this->names = $names;
        $this->holiday = $holiday;
    }

    public function __toString()
    {
        return sprintf("(substitute) %s", $this->holiday);
    }
}
