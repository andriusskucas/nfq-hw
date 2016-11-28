<?php
namespace WeatherBundle\HTTPClients;

use GuzzleHttp\Client;

class YahooHttpClient
{

    /**
     * @param String $url
     * @param array $parameters
     * @return String
     */
    public function get(String $url, array $parameters): String
    {
        $client = new Client([
            'base_uri' => $url,
            'timeout' => 2.0,
        ]);

        $yql_query = 'select * from weather.forecast where woeid in (SELECT woeid FROM geo.places WHERE text="(' .
            $parameters['latitude'] . ',' .
            $parameters['longitude'] . ')") and u="c"';

        $response = $client->request('GET', "?q=" . urlencode($yql_query) . "&format=json");
        return $response->getBody();
    }
}