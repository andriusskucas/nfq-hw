<?php
namespace Weather;
use Symfony\Component\Config\Definition\Exception\Exception;

class WeatherException extends Exception
{
    public function errorMessage() {
        //error message
        $errorMsg = __CLASS__.' '.$this->getMessage();
        return $errorMsg;
    }
}