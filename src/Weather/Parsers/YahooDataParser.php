<?php

namespace Weather\Parsers;

class YahooDataParser
{


    /**
     * @param $data
     * @return Int
     */
    public function parseTemperature($data): Int
    {
        $allWeatherInfo = json_decode($data);

        if (!isset($allWeatherInfo->query->results->channel->item->condition->temp)
            || empty($allWeatherInfo->query->results->channel->item->condition->temp)
        ) {
            throw new WeatherException("Could not load Weather data");
        }

        return $allWeatherInfo->query->results->channel->item->condition->temp;
    }
}