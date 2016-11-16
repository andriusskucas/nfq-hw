<?php

namespace Weather;

use Weather\Weather;
use Weather\WeatherProviderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use DateTime;


class CachedWeatherProvider implements WeatherProviderInterface
{
    private $cacheFileName;
    private $provider;
    private $weather;
    private $file;
    private $decodedFile;


    public function __construct($provider)
    {
        $this->cacheFileName = 'cachedWeather.json';
        $this->provider = $provider;
    }

    public function fetch(Location $location)
    {
        $fs = new Filesystem();

        if($fs->exists($this->cacheFileName)){
            $this->file = json_decode(file_get_contents($this->cacheFileName),true);

            if(isset($this->file[$location->getLatitude().'_'.$location->getLongitude()])){
                $currentLocationData = $this->file[$location->getLatitude().'_'.$location->getLongitude()];
                $date = date('YmdHis');
                if($date - $currentLocationData['last_updated'] > 100){
                    $this->getNewData($location,$fs);
                }else{
                    $this->weather = new Weather($currentLocationData['temp']);
                }

            }else{
                $this->getNewData($location,$fs);
            }
        }else{
            $this->getNewData($location,$fs);
        }


        return $this->weather;
    }

    private function getNewData($location,$fs)
    {
        echo 'Atnaujino';
        $this->weather = $this->provider->fetch($location);

        // Form array
        $infoArray = [];
        if(count($this->file) > 0)
            $infoArray = $this->file;

        $date = date('YmdHis');
        $infoArray[$location->getLatitude().'_'.$location->getLongitude()] = ['temp'=>$this->weather->getTemperature(),'last_updated'=>$date];
        $json = json_encode($infoArray);
        try {
            $fs->dumpFile($this->cacheFileName, $json);
        }
        catch(IOException $e) {
            // Do nothing
            echo $e->getMessage();
        }
    }
}