<?php

namespace Cadulis\Sdk\Model;

class CustomerOpeningHours
{
    protected $openingHours = [];

    public function addOpeningHour(array $days, int $startTime, int $endTime)
    {
        array_map(
            function ($day) {
                if (!is_int($day) || $day < 0 || $day > 6) {
                    throw new \InvalidArgumentException('Wrong day format, must be an integer between 0 and 6');
                }
            },
            $days
        );

        if (!is_int($startTime) || $startTime < 0 || $startTime > 86400) {
            throw new \InvalidArgumentException('Wrong start time format, must be an integer between 0 and 86400');
        }
        if (!is_int($endTime) || $endTime < 0 || $endTime > 86400) {
            throw new \InvalidArgumentException('Wrong start time format, must be an integer between 0 and 86400');
        }

        $this->openingHours[] = [
            'day'   => $days,
            'start' => $startTime,
            'end'   => $endTime,
        ];
    }

    public function addOpeningHourByTimeString(array $days, string $startTime, string $endTime)
    {
        $start = explode(':', $startTime);
        if (count($start) !== 3) {
            throw new \InvalidArgumentException(
                'Invalid opening hour start format, expecting 3 parts separated by :'
            );
        }
        $end = explode(':', $endTime);
        if (count($end) !== 3) {
            throw new \InvalidArgumentException(
                'Invalid opening hour end format, expecting 3 parts separated by :'
            );
        }
        $this->addOpeningHour(
            array_map('intval', $days),
            (int)$start[0] * 3600 + (int)$start[1] * 60 + (int)$start[2],
            (int)$end[0] * 3600 + (int)$end[1] * 60 + (int)$end[2]
        );
    }

    public function getOpeningHours() : array
    {
        return $this->openingHours;
    }

    //#####          #####//

    /**
     * @param string $openingHours eg 1,4|08:00:00|12:00:00;3|10:00:00|12:00:00
     *                             days|start|end;days|start|end
     *                             days are integers (0 for sunday, 1 for monday) separated by coma
     */
    public function hydrate(string $openingHours)
    {
        $data = explode(';', $openingHours);
        foreach ($data as $openingHour) {
            $data1 = explode('|', $openingHour);
            if (count($data1) !== 3) {
                throw new \InvalidArgumentException('Invalid opening hour format, expecting 3 parts separated by |');
            }
            $this->addOpeningHourByTimeString(explode(',', $data1[0]), $data1[1], $data1[2]);
        }
    }

    /**
     * eg : 1,4|08:00:00|12:00:00;3|10:00:00|12:00:00
     */
    public function toString() : string
    {
        $openingHours = [];
        foreach ($this->openingHours as $openingHour) {
            $start = str_pad(floor($openingHour['start'] / 3600), 2, '0', STR_PAD_LEFT)
                . ':' . str_pad(floor(($openingHour['start'] % 3600) / 60), 2, '0', STR_PAD_LEFT)
                . ':' . str_pad(floor(($openingHour['start'] % 60)), 2, '0', STR_PAD_LEFT);
            $end = str_pad(floor($openingHour['end'] / 3600), 2, '0', STR_PAD_LEFT)
                . ':' . str_pad(floor(($openingHour['end'] % 3600) / 60), 2, '0', STR_PAD_LEFT)
                . ':' . str_pad(floor(($openingHour['end'] % 60)), 2, '0', STR_PAD_LEFT);
            $openingHours[] = implode('|', [implode(',', $openingHour['day']), $start, $end]);
        }

        return implode(';', $openingHours);
    }
}