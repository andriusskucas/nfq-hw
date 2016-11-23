<?php

namespace WeatherBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Weather\Location;

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
    public function weatherAction($latitude, $longitude)
    {
        //$location = new Location(54.687157,25.279652);
        $location = new Location($latitude, $longitude);
        $provider = $this->get('cache_weather_service');

        $weather = $provider->fetch($location);

        return $this->render('WeatherBundle:weather:weather.html.twig', [
            'title' => 'Vilniaus temperatūra',
            'city' => 'Vilnius',
            'temp' => $weather->getTemperature()
        ]);
    }
}
