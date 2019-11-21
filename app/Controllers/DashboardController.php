<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use App\Core\Utilities\View;

class DashboardController extends Controller
{
    /**
     * The view instance injected in routes.php
     *
     * @var \App\Core\Utilities\View
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
     * Show the dashboard page.
     *
     * @param   Psr\Http\Message\RequestInterface     $request
     * @return  Zend\Diactoros\Response
     */
    public function index(RequestInterface $request)
    {
        return $this->view->render('dashboard.twig');
    }
}