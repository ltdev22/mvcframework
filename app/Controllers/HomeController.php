<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;
use App\Wrappers\View;
use Doctrine\ORM\EntityManager;

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
    function __construct(View $view, EntityManager $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    /**
     * Respond with the homepage.
     *
     * @param   Psr\Http\Message\RequestInterface     $request
     * @return  Zend\Diactoros\Response
     */
    public function index(RequestInterface $request) : Response
    {
        $test = 'foo';
        $user = $this->db->getRepository(\App\Models\User::class)->find(1);

        // dd($user);

        return $this->view->render('home.twig', compact('test', 'user'));
    }
}