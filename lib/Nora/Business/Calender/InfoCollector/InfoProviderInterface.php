<?php
namespace Nora\Business\Calender\InfoCollector;

interface InfoProviderInterface
{
    public function provide(InfoCollector $collector, &$info);
}
