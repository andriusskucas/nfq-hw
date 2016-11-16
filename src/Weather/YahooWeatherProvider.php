<?php
namespace Weather;
use GuzzleHttp\Client;
use Weather\WeatherProviderInterface;
use Weather\Weather;
use Weather\Location;

class YahooWeatherProvider implements WeatherProviderInterface
{

    private $BASE_URL = 'http://query.yahooapis.com/v1/public/yql';

    public function fetch(Location $location)
    {

        $yql_query = 'select * from weather.forecast where woeid in (SELECT woeid FROM geo.places WHERE text="('.$location->getLatitude().','.$location->getLongitude().')") and u="c"';

        $client = new Client([
            'base_uri'=>$this->BASE_URL,
            'timeout'  => 2.0,
        ]);
        $response = $client->request('GET', "?q=" . urlencode($yql_query) . "&format=json");
        $allWeatherInfo = json_decode($response->getBody());

        if(!isset($allWeatherInfo->query->results->channel->item->condition->temp) || empty($allWeatherInfo->query->results->channel->item->condition->temp)){
            throw new WeatherException("Could not load Weather data");
        }

        return new Weather($allWeatherInfo->query->results->channel->item->condition->temp);
    }
}