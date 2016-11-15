<?php

namespace Weather;

class Weather
{
    private $temparature;

    public function __construct($temparature)
    {
        $this->temparature = $temparature;
    }

    public function getTemperature()
    {
        return $this->temparature;
    }
}