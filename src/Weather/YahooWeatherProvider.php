<?php
namespace Weather;

use Weather\WeatherProviderInterface;
use Weather\Weather;
use Weather\Location;

class YahooWeatherProvider implements WeatherProviderInterface
{
    public function fetch(Location $location)
    {
        return new Weather(20);
    }
}