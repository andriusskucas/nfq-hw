<?php

namespace WeatherBundle\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Weather\CachedWeatherProvider;
use Weather\Location;
use Weather\WunderGroundWeatherProvider;
use Weather\YahooWeatherProvider;
use Weather\DelegateWeatherProvider;

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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/weather/{latitude}/{longitude}")
     */
    public function weatherAction($latitude,$longitude)
    {
        //$location = new Location(54.687157,25.279652);
        $location = new Location($latitude,$longitude);
        $provider = new CachedWeatherProvider(
            new YahooWeatherProvider()
        );
        $weather = $provider->fetch($location);

        return $this->render('WeatherBundle:weather:weather.html.twig',[
            'title' => 'Vilniaus temperatūra',
            'city'=>'Vilnius',
            'temp'=>$weather->getTemperature()
        ]);
    }
}
