<?php

namespace Hongliang\Weather\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function home()
    {
        return new Response(
            '<html><body><h1>Welcome to the weather service.</h1></body></html>'
        );
    }
}
