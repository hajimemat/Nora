<?php
namespace Nora\Business\Calender\Holiday;

use Nora\Business\Calender\InfoCollector\InfoProviderInterface;

interface HolidayProviderInterface extends InfoProviderInterface
{
    public function getContainer(string $date) : HolidayContainerInterface;
}
