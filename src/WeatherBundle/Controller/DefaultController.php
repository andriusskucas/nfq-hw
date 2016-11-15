<?php

namespace WeatherBundle\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Weather\Location;
use Weather\YahooWeatherProvider;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('WeatherBundle:Default:index.html.twig');
    }

    /**
     * @param string $city
     * @return array
     */
    private function getWeatherByCity($city)
    {
        $cityWeather = [];
        $baseUrl = 'http://api.wunderground.com/api/';
        $apiKey = '6d59babb57804f5e';
        $keys = '/conditions/q/CA/';
        $end = $city.'.json';
        // Construct query url
        $url = $baseUrl.$apiKey.$keys;

        // Make call with Guzzle
        $client = new Client([
            'base_uri'=>$url,
            'timeout'  => 2.0,
        ]);
        $response = $client->request('GET', $end);

        // Convert JSON to PHP object
        $allWeatherInfo = json_decode($response->getBody());

        // Parse needed information
        $cityWeather['icon'] = $allWeatherInfo->current_observation->icon_url;
        $cityWeather['temp'] = $allWeatherInfo->current_observation->temp_c;

        return $cityWeather;
    }

    /**
     * @param string $city
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/weather/{city}")
     */
    public function weatherAction($city)
    {
        $location = new Location(22.1,22.3);
        $provider = new YahooWeatherProvider();
        $weather = $provider->fetch($location);

        $cityWeather = $this->getWeatherByCity($city);

        return $this->render('WeatherBundle:weather:weather.html.twig',[
            'title' => $city.' temperatūra',
            'city'=>$city,
            'cityWeather'=>$cityWeather
        ]);
    }
}
