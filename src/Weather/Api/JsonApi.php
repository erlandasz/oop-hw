<?php

namespace Weather\Api;

use Weather\Model\NullWeather;
use Weather\Model\Weather;

class JsonApi implements DataProvider
{
    /**
     * @param \DateTime $date
     * @return Weather
     */
    public function selectByDate(\DateTime $date): Weather
    {
        $items = $this->selectAll();
        $result = new NullWeather();

        foreach ($items as $item) {
            if ($item->getDate()->format('Y-m-d') === $date->format('Y-m-d')) {
                $result = $item;
            }
        }

        return $result;
    }

    public function selectByRange(\DateTime $from, \DateTime $to): array
    {
        $items = $this->selectAll();
        $result = [];

        foreach ($items as $item) {
            if ($item->getDate() >= $from && $item->getDate() <= $to) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @return Weather[]
     */
    private function selectAll(): array
    {
        $result = [];
        $data = json_decode(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'Db' . DIRECTORY_SEPARATOR . 'Weather.json'),
            true
        );
        foreach ($data as $item) {
            $record = new Weather();
            $record->setDate(new \DateTime($item['date']));
            $record->setDayTemp($item['high']);
            $record->setNightTemp($item['low']);
            $record->setSky($this->textToIconNo($item['text']));
            $result[] = $record;
        }

        return $result;
    }

    /**
     * @param string $iconNo
     * @return int
     */
    private function textToIconNo(string $iconNo): int
    {
        switch (trim($iconNo))
        {
            case 'Scattered Showers':
                $iconNo = 2;
                break;
            case 'Partly Cloudy':
            case 'Mostly Cloudy':
            case 'Cloudy':
                $iconNo = 1;
                break;
            case 'Breezy':
            case 'Sunny':
                $iconNo = 3;
                break;
        }
        return $iconNo;
    }
}
