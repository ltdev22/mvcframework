<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use League\Route\Router;
use App\Utilities\View;
use App\Core\Auth\Auth;
use App\Session\FlashSession;

class LoginController extends Controller
{
    /**
     * The view instance injected in routes.php
     *
     * @var \App\Utilities\View
     */
    protected $view;

    /**
     * The auth instance.
     *
     * @var \App\Core\Auth\Auth
     */
    protected $auth;

    /**
     * The route instance.
     *
     * @var \League\Route\Router
     */
    protected $route;

    /**
     * The flash session instance.
     *
     * @var \App\Session\FlashSession
     */
    protected $flash;

    /**
     * Instatiate the controller
     *
     * @param   \App\Utilities\View     $view
     * @param   \App\Core\Auth\Auth          $auth
     * @param   \League\Route\Router    $route
     * @param   \App\Session\FlashSession $flash
     * @return  void
     */
    public function __construct(
        View $view,
        Auth $auth,
        Router $route,
        FlashSession $flash
    )
    {
        $this->view = $view;
        $this->auth = $auth;
        $this->route = $route;
        $this->flash = $flash;
    }

    /**
     * Show the login page.
     *
     * @param   Psr\Http\Message\RequestInterface     $request
     * @return  Zend\Diactoros\Response
     */
    public function index(RequestInterface $request)
    {
        return $this->view->render('auth/login.twig');
    }

    /**
     * Handle the user login.
     *
     * @param  RequestInterface $request
     * @return \Zend\Diactoros\Response\RedirectResponse
     */
    public function login(RequestInterface $request)
    {
        // Validate the form
        $data = $this->validate($request, [
            'email'     => ['required', 'email'],
            'password'  => ['required'],
        ]);

        // Try and login the user
        if(!$this->auth->attempt($data['email'], $data['password'], isset($data['remember']))) {
            $this->flash->now('error', 'Could not signed you in with those credentials.');

            return redirectTo($request->getUri()->getPath());
        }

        // Redirect to home page
        return redirectTo($this->route->getNamedRoute('home')->getPath());
    }
}