<?php

namespace App\Wrappers;

use Twig\Environment as Twig;

class View
{
    /**
     * Will hold the new Twig instance created in \App\Providers\ViewServiceProvider.
     *
     * @var this
     */
    protected $twig;

    /**
     * Create new View instance
     *
     * @param   \Twig    $twig  the twig instance
     * @return  void
     */
    function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render the response comming from the controllers
     * 
     * @param   Zend\Diactoros\Response $response
     * @return  Zend\Diactoros\Response
     */
    public function render(Response $response): Response
    {
        $response->getBody()->write('Hello World from render!');
        return $response;
    }
}