<?php

namespace Weather;

class Weather
{
    private $temperature;

    /**
     * Weather constructor.
     * @param int $temparature
     */
    public function __construct(int $temperature)
    {
        $this->temparature = $temperature;
    }

    /**
     * @return int
     */
    public function getTemperature(): int
    {
        return $this->temparature;
    }
}