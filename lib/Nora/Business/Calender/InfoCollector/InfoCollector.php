<?php
namespace Nora\Business\Calender\InfoCollector;

use Nora\Business\Calender\DateTime\DateTimeInterface;

class InfoCollector
{
    /**
     * @var DateTimeInterface
     */
    private $date;
    /**
     * @var array collected info
     */
    private $info = [];

    public function __construct(
        DateTimeInterface $date
    ) {
        $this->date = $date;
    }

    public function collect(InfoProviderInterface $provider)
    {
        $provider->provide($this, $this->info);
    }

    public function getDateTime() : DateTimeInterface
    {
        return $this->date;
    }

    public function getInfo() : array
    {
        return $this->info;
    }
}
