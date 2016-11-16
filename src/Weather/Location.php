<?php

namespace Weather;

class Location
{
    private $longitude;

    private $latitude;

    public function __construct( $latitude, $longitude)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }
}