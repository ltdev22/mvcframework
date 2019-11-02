<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;
use App\Wrappers\View;

class HomeController extends Controller
{
    /**
     * The view instance injected in routes.php
     *
     * @var $this
     */
    protected $view;

    /**
     * Instatiate the controller
     *
     * @param   \App\Wrappers\View     $view
     * @return  void
     */
    function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * Respond with the homepage.
     *
     * @param   Psr\Http\Message\RequestInterface     $request
     * @return  Zend\Diactoros\Response
     */
    public function index(RequestInterface $request) : Response
    {
        return $this->view->render(new Response);
    }
}