<?php

namespace Hongliang\Weather\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ApiV1Controller extends AbstractController
{
    public function current(Request $request, $location)
    {
        $location = trim($location);
        $provider = $request->query->get('provider');

        switch ($provider) {
            case 'owm':
            case 'openweathermap':
                $p = new \Hongliang\Weather\Provider\OpenWeatherMapProvider();
                $p->setApiKey($this->getParameter('openweathermap_api_key'));
                break;
            case 'heweather':
            default:
                $p = new \Hongliang\Weather\Provider\HeWeatherProvider();
                $p->setApiKey($this->getParameter('heweather_api_key'));
                break;
        }

        // try cache
        $cacheFile = $p->getCacheDir().'/'.date('Ymd').'_current_'.$location;
        if (file_exists($cacheFile)) {
            $res = file_get_contents($cacheFile);
        } else {
            $p->setPlaceByName($location);
            $res = $p->getCurrent()->serialize();
        }

        $response = new Response($res, Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
