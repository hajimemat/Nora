<?php
namespace Nora\Business\Calender\Holiday\Japan;

use Nora\Business\Calender\DateTime\DateTimeInterface;
use Nora\Business\Calender\Holiday\HolidayContainer;
use Nora\Business\Calender\Holiday\HolidayContainerInterface;
use Nora\Business\Calender\Holiday\HolidayProviderInterface;
use Nora\Business\Calender\Holiday\Japan\Logic;
use Nora\Business\Calender\InfoCollector\InfoCollector;

class Provider implements HolidayProviderInterface
{
    const ID = 'holiday';

    private $cachedHolidays = [];
    private $dates = [];

    /**
     * Holiday Logic List
     *
     * @var array
     */
    public static $holidays = [
        Logic\NationalFundationDay::class,
        Logic\ShowaDay::class,
        Logic\ChildrensDay::class,
        Logic\CultureDay::class,
        Logic\LaborThanksgivingDay::class,
        Logic\EmperorsBirthday::class,
        Logic\VernalEquinoxDay::class,
        Logic\ComingOfAgeDay::class,
        Logic\GreeneryDay::class,
        Logic\MarineDay::class,
        Logic\MountainDay::class,
        Logic\RespectForTheAgeDay::class,
        Logic\SportsDay::class,
        Logic\AutumnalEquinoxDay::class,
        Logic\ConstitutionMemorialDay::class,
        Logic\SubstituteHolidays::class,
        Logic\CoronationDay::class,
        Logic\EnthronementProclamationCeremony::class,
        Logic\BridgeHolidays::class
    ];

    public function isHoliday(DateTimeInterface $date) : bool
    {
        $year = $date->format('Y');

        // その年の情報をロードする
        $this->load($year);

        // foreach ($this->dates as $day) {
        //     printf("%s\n", $day);
        // }

        return $this->getContainer($date->format('Y-m-d'))->hasHoliday();
    }

    public function load($year)
    {
        if (isset($this->loaded[$year])) {
            return;
        }
        // 再読込防止
        $this->loaded[$year] = true;

        // 年間の休日を生成する
        $this->genHolidays($year);
    }

    public function getHolidays()
    {
        return array_map(function ($v) {
            return $v;
        }, array_filter($this->dates, function ($v) {
            return $v->hasHoliday();
        }));
    }

    public function getHoliday(DateTimeInterface $date)
    {
        return $this->isHoliday($date) ?
            $this->getContainer($date->format('Y-m-d'))->getHoliday()->getName():
            false;
    }

    public function genHolidays(int $year)
    {
        if (array_key_exists($year, $this->cachedHolidays)) {
            return true;
        }
        foreach (static::$holidays as $holiday) {
            (new $holiday)($this, $year);
        }
        ksort($this->dates);
    }

    public function getContainer(string $date) : HolidayContainerInterface
    {
        if (!array_key_exists($date, $this->dates)) {
            $this->dates[$date] = new HolidayContainer($date);
        }
        return $this->dates[$date];
    }

    public function provide(InfoCollector $collector, &$info)
    {
        $date = $collector->getDateTime();
        $info['holiday'] = $this->getHoliday($date);
    }
}
