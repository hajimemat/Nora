<?php
namespace NoraFake;

class FakeMyName
{
    public $name;
    public $dispName;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setHogeComp(FakeComponent $comp)
    {
        $this->comp = $comp;
    }

    public function initialize()
    {
        $this->dispName = $this->name.'@'.$this->company;
    }
}
