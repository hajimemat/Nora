<?php
namespace NoraFake;

use Nora\Business\Calender\DateTime\DateTime;
use Nora\Business\Calender\DateTime\DateTimeInterface;
use Nora\Business\Calender\InfoCollector\InfoCollector;
use Nora\Business\Calender\InfoCollector\InfoProviderInterface;

class FakeDatetime extends DateTime
{
    public function configure()
    {
        $this->addInfoProvider(new class() implements InfoProviderInterface {
            const ID = 'birthday';

            private $birthday = [
                '01-18' => 'hajime',
            ];

            public function provide(InfoCollector $collector, &$info)
            {
                if ($collector->getDateTime()->format('m-d') === '01-18') {
                    $info['birthday'] = ['hajime'];
                } else {
                    $info['birthday'] = [];
                }
            }

            public function isBirthday(DateTimeInterface $date)
            {
                if (array_key_exists($date->format('m-d'), $this->birthday)) {
                    return true;
                }
                return false;
            }
        });
    }
}
