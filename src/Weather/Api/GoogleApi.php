<?php

namespace Weather\Api;

use Weather\Model\NullWeather;
use Weather\Model\Weather;

class GoogleApi
{
    /**
     * @return Weather
     * @throws \Exception
     */
    public function getToday()
    {
        $today = $this->load(new NullWeather());
        $today->setDate(new \DateTime());

        return $today;
    }
    public function getWeek()
    {
        $week = [];
        for($i=0; $i <= 6; $i++)
        {
            $eachDay = $this->load(new NullWeather());
            $eachDay->setDate(new \DateTime($i . 'day'));
            $week[]=$eachDay;
        }
        return $week;
    }
    /**
     * @param Weather $before
     * @return Weather
     * @throws \Exception
     */
    private function load(Weather $before)
    {
        $now = new Weather();
        $base = $before->getDayTemp();
        $now->setDayTemp(random_int(5 - $base, 5 + $base));

        $base = $before->getNightTemp();
        $now->setNightTemp(random_int(-5 - abs($base), -5 + abs($base)));

        $now->setSky(random_int(1, 3));

        return $now;
    }
}
