<?php
namespace Nora\Business\Calender;

use DateTime as OriginalDateTime;
use DateTimeZone;
use Nora\Business\Calender\DateTime\DateTimeInterface;
use Nora\Business\Calender\InfoCollector\InfoCollector;
use Nora\Business\Calender\InfoCollector\InfoProviderInterface;

class CalenderFacade
{
    private $provider;

    public function __construct(
        InfoProviderInterface $provider
    ) {
        $this->providers = [$provider];
    }

    public function getInfo(DateTimeInterface $date)
    {
        $collector = new InfoCollector($date);
        foreach ($this->providers as $provider) {
            $collector->collect($provider);
        }
        return $collector->getInfo();
    }

    public function addProvider(InfoProviderInterface $provider)
    {
        $this->providers[$provider::ID] = $provider;
    }

    public function getProvider(string $ID)
    {
        return $this->providers[$ID];
    }

    public function __call($name, $args)
    {
        foreach ($this->providers as $provider) {
            if (is_callable([$provider, $name])) {
                return call_user_func_array([$provider, $name], $args);
            }
        }
        throw new \InvalidArgumentException($name);
    }
}
