<?php

namespace Weather;

use Weather\WeatherProviderInterface;
use Weather\Weather;

class OpenMapWeatherProvider implements WeatherProviderInterface
{
    public function fetch(Location $location)
    {
        return new Weather();
    }
}