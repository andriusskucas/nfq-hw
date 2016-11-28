<?php

namespace WeatherBundle\Providers;

use WeatherBundle\Location;
use WeatherBundle\Providers\WeatherProviderInterface;
use WeatherBundle\Weather;

class DelegateWeatherProvider implements WeatherProviderInterface
{
    private $providers;
    private $weather;


    /**
     * DelegateWeatherProvider constructor.
     * @param array $providers
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    /**
     * @param Location $location
     * @return Weather
     */
    public function fetch(Location $location): Weather
    {

        $i = 0;
        $dataLoaded = false;
        while ($i < count($this->providers) && !$dataLoaded) {
            $dataLoaded = $this->tryGetData($this->providers[$i++], $location);
        }

        if (!$dataLoaded)
            throw new WeatherException("None of the providers work");

        return $this->weather;
    }

    

    /**
     * @param WeatherProviderInterface $provider
     * @param Location $location
     * @return bool
     */
    private function tryGetData(WeatherProviderInterface $provider, Location $location): bool
    {
        try {
            $this->weather = $provider->fetch($location);

        } catch (WeatherException $e) {
            return false;
        }
        return true;
    }
}