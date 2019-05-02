<?php

namespace Weather;

use Weather\Api\DataProvider;
use Weather\Api\DbRepository;
use Weather\Api\GoogleApi;
use Weather\Model\Weather;

class Manager
{
    /**
     * @var DataProvider
     */
    private $transporter;

    public function getTodayInfo(string $source): Weather
    {
        return $this->getTransporter($source)->selectByDate(new \DateTime());
    }

    public function getWeekInfo(string $source): array
    {
        return $this->getTransporter($source)->selectByRange(new \DateTime('midnight'), new \DateTime('+6 days midnight'));
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
        }

        return $this->transporter;
    }
}


