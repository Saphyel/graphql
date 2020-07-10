<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemoController
{
    /**
     * @Route("/ok")
     */
    public function index(): Response
    {
        return new Response('OK');
    }
}
