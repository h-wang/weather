<?php

namespace Hongliang\Weather\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiV1Controller extends AbstractController
{
    public function current($location)
    {
        $p = new \Hongliang\Weather\Provider\HeWeatherProvider();
        $p->setApiKey($this->getParameter('heweather_api_key'));
        $p->setPlaceByName($location);
        $res = $p->getCurrent()->serialize();
        
        $response = new Response($res, Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}
