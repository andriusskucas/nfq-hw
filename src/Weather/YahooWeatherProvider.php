<?php
namespace Weather;

use Weather\Parsers\YahooDataParser;
use Weather\WeatherProviderInterface;
use Weather\Weather;
use Weather\Location;

class YahooWeatherProvider implements WeatherProviderInterface
{

    protected $base_url;
    protected $httpClient;
    protected $dataParser;

    /**
     * YahooWeatherProvider constructor.
     * @param $httpClient
     * @param YahooDataParser $dataParser
     * @param String $base_url
     */
    public function __construct($httpClient, YahooDataParser $dataParser, String $base_url)
    {
        $this->HTTPClient = $httpClient;
        $this->dataParser = $dataParser;
        $this->base_url = $base_url;
    }

    /**
     * @param \Weather\Location $location
     * @return \Weather\Weather
     */
    public function fetch(Location $location): Weather
    {



        $response = $this->HTTPClient->get($this->base_url, ['latitude' => $location->getLatitude(), 'longitude' => $location->getLongitude()]);

        $temperature = $this->dataParser->parseTemperature($response);

        return new Weather($temperature);
    }
}