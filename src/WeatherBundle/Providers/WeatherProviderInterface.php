<?php
namespace WeatherBundle\Providers;

use WeatherBundle\Location;
interface WeatherProviderInterface
{
    public function fetch(Location $location);
}