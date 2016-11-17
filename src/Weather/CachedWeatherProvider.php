<?php

namespace Weather;

use Weather\Weather;
use Weather\WeatherProviderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class CachedWeatherProvider implements WeatherProviderInterface
{
    const CASHED_WEATHER_FILENAME = 'cachedWeather.json';
    private $provider;
    private $file;


    /**
     * CachedWeatherProvider constructor.
     * @param \Weather\WeatherProviderInterface $provider
     */
    public function __construct(WeatherProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param Location $location
     * @return \Weather\Weather
     */
    public function fetch(Location $location): Weather
    {
        $fs = new Filesystem();

        if (!$fs->exists(self::CASHED_WEATHER_FILENAME))
            return $this->getNewData($location, $fs);

        $this->file = json_decode(file_get_contents(self::CASHED_WEATHER_FILENAME), true);
        $current_location = $location->getLatitude() . '_' . $location->getLongitude();

        if (!isset($this->file[$current_location]))
            return $this->getNewData($location, $fs);

        $currentLocationData = $this->file[$location->getLatitude() . '_' . $location->getLongitude()];
        $date = date('YmdHis');

        if ($date - $currentLocationData['last_updated'] > 100)
            return $this->getNewData($location, $fs);


        return new Weather($currentLocationData['temp']);
    }

    /**
     * @param Location $location
     * @param Filesystem $fs
     * @return \Weather\Weather
     */
    private function getNewData(Location $location, Filesystem $fs): Weather
    {
        $weather = $this->provider->fetch($location);

        // Form array
        $infoArray = [];
        if (count($this->file) > 0)
            $infoArray = $this->file;

        $date = date('YmdHis');
        $infoArray[$location->getLatitude() . '_' . $location->getLongitude()] =
            ['temp' => $weather->getTemperature(), 'last_updated' => $date];

        $json = json_encode($infoArray);

        try {
            $fs->dumpFile(self::CASHED_WEATHER_FILENAME, $json);
        } catch (IOException $e) {
            // Do nothing
        }

        return $weather;
    }

}