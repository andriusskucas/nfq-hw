<?php
namespace Weather;

interface WeatherProviderInterface
{

    public function fetch(Location $location);
}