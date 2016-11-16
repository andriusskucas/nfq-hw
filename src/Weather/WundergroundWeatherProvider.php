<?php

namespace Weather;

use GuzzleHttp\Client;
use Weather\WeatherProviderInterface;
use Weather\Weather;
use Weather\WeatherException;

class WunderGroundWeatherProvider implements WeatherProviderInterface
{
    private $base_url = 'http://api.wunderground.com/api/';
    private $API_KEY = '6d59babb57804f5e';

    public function fetch(Location $location)
    {
        $client = new Client([
            'base_uri'=>$this->base_url,
            'timeout'  => 2.0,
        ]);
        $response = $client->request('GET', $this->API_KEY.'/conditions/q/'.$location->getLatitude().','.$location->getLongitude().'.json');
        $allWeatherInfo = json_decode($response->getBody());

        if(!isset($allWeatherInfo->current_observation) || $allWeatherInfo->current_observation->temp_c == null){
            throw new WeatherException("Could not load Weather data");
        }

        return new Weather($allWeatherInfo->current_observation->temp_c);
    }
}