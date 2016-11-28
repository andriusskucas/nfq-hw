<?php
namespace WeatherBundle\HTTPClients;

use GuzzleHttp\Client;

class WundergroundHttpClient
{


    /**
     * @param String $url
     * @param array $parameters
     * @param String $api_key
     * @return String
     */
    public function get(String $url, array $parameters, String $api_key): String
    {
        $client = new Client([
            'base_uri' => $url,
            'timeout' => 2.0,
        ]);


        $response = $client->request(
            'GET',
            $api_key . '/conditions/q/' . $parameters['latitude'] . ',' . $parameters['longitude'] . '.json'
        );
        return $response->getBody();
    }
}