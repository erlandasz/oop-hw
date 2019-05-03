<?php

namespace Weather;

use Weather\Api\DataProvider;
use Weather\Api\DbRepository;
use Weather\Api\GoogleApi;
use Weather\Api\JsonApi;
use Weather\Model\Weather;

class Manager
{
    /**
     * @var DataProvider
     */
    private $transporter;

    public function getTodayInfo(string $source): Weather
    {
        if ($source === 'db')
            return $this->getTransporter($source)->selectByDate(new \DateTime());
        if ($source === 'googleApi')
            return $this->getTransporter($source)->getToday();
        if ($source === 'JSON')
            return $this->getTransporter($source)->selectByDate(new \DateTime());
    }

    public function getWeekInfo(string $source): array
    {
        if ($source === 'db')
            return $this->getTransporter($source)->selectByRange(new \DateTime('midnight'), new \DateTime('+6 days midnight'));
        if ($source === 'googleApi')
            return $this->getTransporter($source)->getWeek();
        if ($source === 'JSON')
            return $this->getTransporter($source)->selectByDate(new \DateTime());
    }

    private function getTransporter(string $source)
    {
        if (null === $this->transporter) {
            if ($source === 'db')
            {
                $this->transporter = new DbRepository();
            }
            if ($source === 'googleApi')
            {
                $this->transporter = new GoogleApi();
            }
            if ($source === 'JSON')
            {
                $this->transporter = new JsonApi();
            }
        }

        return $this->transporter;
    }
}


