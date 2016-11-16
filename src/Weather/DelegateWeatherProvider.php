<?php

namespace Weather;

use Weather\WeatherProviderInterface;

class DelegateWeatherProvider implements WeatherProviderInterface
{
    private $providers;
    private $weather;

    public function __construct($providers)
    {
        $this->providers = $providers;
    }

    /**
     * @param Location $location
     * @return Weather
     */
    public function fetch(Location $location)
    {

        try{
            foreach ($this->providers as $provider){
                $this->weather = $provider->fetch($location);
            }
        }catch (WeatherException $e){
            // do nothing
        }
        return $this->weather;
    }
}