<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use App\Auth\Auth;

class LogoutController extends Controller
{
    /**
     * The auth instance
     *
     * @var \App\Auth\Auth
     */
    protected $auth;

    /**
     * Instatiate the controller
     *
     * @param   \App\Auth\Auth     $auth
     * @return  void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Logout.
     *
     * @param   Psr\Http\Message\RequestInterface     $request
     * @return  Zend\Diactoros\Response
     */
    public function logout(RequestInterface $request)
    {
        $this->auth->logout();

        return redirectTo('/');
    }
}