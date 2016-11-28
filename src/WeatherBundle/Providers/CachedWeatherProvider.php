<?php

namespace WeatherBundle\Providers;

use WeatherBundle\Weather;
use WeatherBundle\Location;
use WeatherBundle\Providers\WeatherProviderInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;


class CachedWeatherProvider implements WeatherProviderInterface
{

    private $provider;
    private $ttl;


    /**
     * CachedWeatherProvider constructor.
     * @param WeatherProviderInterface $provider
     * @param Int $ttl
     */
    public function __construct(WeatherProviderInterface $provider, Int $ttl)
    {
        $this->provider = $provider;
        $this->ttl = $ttl;
    }

    /**
     * @param Location $location
     * @return Weather
     */
    public function fetch(Location $location): Weather
    {

        $current_location = $location->getLatitude() . '_' . $location->getLongitude();

        $cache = new FilesystemAdapter();
        $temperatureItem = $cache->getItem($current_location);
        $temperatureItem->expiresAfter($this->ttl);

        if (!$temperatureItem->isHit()) {
            $weather = $this->provider->fetch($location);
            $temperatureItem->set(serialize($weather->getTemperature()));

            $cache->save($temperatureItem);
            return $weather;
        }

        $temperature = $temperatureItem->get();
        $temperature = unserialize($temperature);

        return new Weather($temperature);
    }

}