<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;
use App\Core\Utilities\View;

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
     * @param   \App\Core\Utilities\View     $view
     * @return  void
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * Respond with the homepage.
     *
     * @param   Psr\Http\Message\RequestInterface     $request
     * @return  Zend\Diactoros\Response
     */
    public function index(RequestInterface $request)
    {
        return $this->view->render('home.twig');
    }
}