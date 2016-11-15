<?php

namespace Weather;

use Weather\WeatherProviderInterface;

class DelegateWeatherProvider implements WeatherProviderInterface
{
    public function fetch(Location $location)
    {
        return new Weather(20);
    }
}