<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

class HomeController extends Controller
{
    /**
     * Respond with the homepage.
     *
     * @param   Psr\Http\Message\RequestInterface     $request
     * @return  Zend\Diactoros\Response
     */
    public function index(RequestInterface $request) : Response
    {
        $response = new Response;
        $response->getBody()->write('Hello World from inside the controller!');

        return $response;
    }
}