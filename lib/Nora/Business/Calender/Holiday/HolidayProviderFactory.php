<?php
namespace Nora\Business\Calender\Holiday;

class HolidayProviderFactory
{
    public function __invoke(string $name)
    {
        return $this->newProvider($name);
    }

    private function newProvider($name) : HolidayProviderInterface
    {
        $class = sprintf('%s\\%s\\Provider', __NAMESPACE__, ucwords($name));
        return new $class();
    }
}
