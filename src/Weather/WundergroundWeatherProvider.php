<?php

namespace Weather;

use GuzzleHttp\Client;
use Weather\WeatherProviderInterface;
use Weather\Weather;
use Weather\WeatherException;

class WunderGroundWeatherProvider implements WeatherProviderInterface
{
    const BASE_URL = "http://api.wunderground.com/api/";
    const API_KEY = '6d59babb57804f5e';

    /**
     * @param Location $location
     * @return \Weather\Weather
     */
    public function fetch(Location $location): Weather
    {
        $client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout' => 2.0,
        ]);
        $response = $client->request(
            'GET',
            self::API_KEY . '/conditions/q/' . $location->getLatitude() . ',' . $location->getLongitude() . '.json'
        );
        $allWeatherInfo = json_decode($response->getBody());

        if (!isset($allWeatherInfo->current_observation) || $allWeatherInfo->current_observation->temp_c == null) {
            throw new WeatherException("Could not load Weather data");
        }

        return new Weather($allWeatherInfo->current_observation->temp_c);
    }
}