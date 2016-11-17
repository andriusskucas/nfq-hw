<?php
namespace Weather;

use \Exception;

class WeatherException extends Exception
{
    /**
     * WeatherException constructor.
     * @param null $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $extendedMessage = __CLASS__ . ' ' . $message;
        parent::__construct($extendedMessage, $code, $previous);
    }
}