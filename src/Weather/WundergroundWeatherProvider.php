<?php

namespace Weather;

use GuzzleHttp\Client;
use Weather\HTTPClients\WundergroundHttpClient;
use Weather\Parsers\WundergroundDataParser;
use Weather\WeatherProviderInterface;
use Weather\Weather;
use Weather\WeatherException;

class WundergroundWeatherProvider implements WeatherProviderInterface
{

    protected $base_url;
    protected $api_key;
    protected $httpClient;
    protected $dataParser;

    /**
     * WundergroundWeatherProvider constructor.
     * @param WundergroundHttpClient $httpClient
     * @param WundergroundDataParser $dataParser
     * @param String $base_url
     * @param String $api_key
     */
    public function __construct(WundergroundHttpClient $httpClient, WundergroundDataParser $dataParser, String $base_url, String $api_key)
    {
        $this->HTTPClient = $httpClient;
        $this->dataParser = $dataParser;
        $this->base_url = $base_url;
        $this->api_key = $api_key;
    }

    /**
     * @param Location $location
     * @return \Weather\Weather
     */
    public function fetch(Location $location): Weather
    {

        $response = $this . $this->HTTPClient->get(
                $this->base_url,
                ['latitude' => $location->getLatitude(), 'longitude' => $location->getLongitude()],
                $this->api_key
            );

        $temperature = $this->dataParser->parseTemperature($response);

        return new Weather($temperature);
    }
}