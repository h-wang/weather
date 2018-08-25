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

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

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
        if (file_exists($cacheFile = $p->getCacheDir().'/'.date('Ymd').'_current_'.$location)) {
            $res = file_get_contents($cacheFile);
        } else {
            try {
                $res = $p->setPlaceByName($location)->getCurrent()->serialize();
            } catch (\Exception $e) {
                return $response->setStatusCode(Response::HTTP_BAD_REQUEST)
                    ->setContent(json_encode(['error' => $e->getMessage()]));
            }
        }

        return $response->setStatusCode(Response::HTTP_OK)->setContent($res);
    }
}
