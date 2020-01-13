<?php
namespace Nora\Business\Calender\DateTime;

use DateTime as OriginalDateTime;
use DateTimeZone;
use Nora\Business\Calender\CalenderFacade;
use Nora\Business\Calender\CalenderFacadeFactory;
use Nora\Business\Calender\InfoCollector\InfoProviderInterface;

class DateTime extends OriginalDateTime implements DateTimeInterface
{
    private $facade;
    private $timezone;

    public function __construct(string $time = 'now', DateTimeZone $timezone = null, CalenderFacade $facade = null)
    {
        if (!$facade instanceof CalenderFacade) {
            // 初期化
            $this->facade = (new CalenderFacadeFactory)($timezone ?? new DateTimeZone('Asia/Tokyo'));
            $this->configure();
        } else {
            $this->facade = $facade;
        }
        parent::__construct($time, $timezone);
    }

    /**
     * Called When Faade Initialized
     *
     * For Extens Calender Override This Method.
     */
    protected function configure()
    {
    }

    public function __invoke(string $str)
    {
        return new self($str, $this->timezone, $this->facade);
    }

    public function getInfo() : array
    {
        return $this->facade->getInfo($this);
    }

    protected function addInfoProvider(InfoProviderInterface $provider)
    {
        return $this->facade->addProvider($provider);
    }

    public function __call($name, $args)
    {
        return $this->facade->__call($name, [$this]);
    }


    // /**
    //  * @var ProviderInterface
    //  */
    // private $provider;
    //
    // /**
    //  * the hour when the day ends at
    //  * if it's over 24 O'clock just add 24
    //  *
    //  * - 05:00 = 29
    //  *
    //  * @var int
    //  */
    // private $whenTheDayEndsAt = 24;
    //
    // public static $default_name = 'Japan';
    //
    //
    // public function __construct(
    //     ProviderInterface $provider = null,
    //     int $whenTheDayEndsAt = 24
    // ) {
    //     if (!$provider instanceof ProviderInterface) {
    //         $this->provider = (new ProviderFactory)(static::$default_name);
    //     }
    //     $this->time = $time ?? time();
    //     $this->whenTheDayEndsAt = $whenTheDayEndsAt;
    // }
    //
    // public function getProvider() : ProviderInterface
    // {
    //     return $this->provider;
    // }
    //
    // // public function setWorkingTime($from, $to)
    // // {
    // //     $this->workingTimeFrom = $from;
    // //     $this->workingTimeTo = $to;
    // // }
    // //
    // public function format(string $format)
    // {
    //     $this->getCurrent()->format($format);
    // }
    //
    // /**
    //  * get currente datetime
    //  */
    // public function getCurrent() : DatetimeObject
    // {
    //     return new DatetimeObject($this, time());
    // }
    // //
    // // public function getRealDate()
    // // {
    // //     return date('Y-m-d', $this->time);
    // // }
    // //
    // // public function getRealHour()
    // // {
    // //     return date('H', $this->time);
    // // }
    // //
    // // public function getDate()
    // // {
    // //     $hour = $this->getHour();
    // //     if ($hour < $this->whenTheDayEndsAt) {
    // //         if ($hour > 24) {
    // //             return date('Y-m-d', strtotime(date('Y-m-d', $this->time) . "-1 day"));
    // //         }
    // //         return date('Y-m-d', $this->time);
    // //     }
    // //     if ($hour > $this->whenTheDayEndsAt) {
    // //         if ($hour < 24) {
    // //             return date('Y-m-d', strtotime(date('Y-m-d', $this->time) . "+1 day"));
    // //         }
    // //     }
    // // }
    // //
    // // public function getHour() : int
    // // {
    // //     $hour = $this->getRealHour();
    // //
    // //     if ($hour < ($this->whenTheDayEndsAt % 24)) {
    // //         return $hour + 24;
    // //     }
    // //     if ($hour > $this->whenTheDayEndsAt) {
    // //         return $hour;
    // //     }
    // //
    // //     return $hour;
    // // }
    // //
    // public function isHoliday() : bool
    // {
    //     return $this->getCurrent()->isHoliday();
    // }
}
