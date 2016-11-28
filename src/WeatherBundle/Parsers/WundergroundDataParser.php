<?php
namespace WeatherBundle\Parsers;

class WundergroundDataParser
{
    public function parseTemperature($data)
    {
        $allWeatherInfo = json_decode($data);

        if (!isset($allWeatherInfo->current_observation) || $allWeatherInfo->current_observation->temp_c == null) {
            throw new WeatherException("Could not load Weather data");
        }

        return $allWeatherInfo->current_observation->temp_c;
    }
}