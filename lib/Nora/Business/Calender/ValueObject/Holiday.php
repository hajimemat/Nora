<?php
namespace Nora\Business\Calender\ValueObject;

class Holiday implements HolidayInterface
{
    private $name;
    /**
     * @var array
     */
    private $names;

    public function __construct($name, array $names)
    {
        $this->name = $name;
        $this->names = $names;
    }

    public function __toString()
    {
        return $this->name." ".implode(",", array_values($this->names));
    }

    public function getName()
    {
        return $this->names['ja'] ?? current($this->names);
    }
}
