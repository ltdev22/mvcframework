<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use App\Utilities\View;
use App\Auth\Auth;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * The auth instance.
     *
     * @var \App\Auth\Auth
     */
    protected $auth;

    /**
     * The view instance injected in routes.php
     *
     * @var \App\Utilities\View
     */
    protected $view;

    /**
     * Instatiate the controller
     *
     * @param   \App\Utilities\View     $view
     * @param   \App\Auth\Auth          $auth
     * @return  void
     */
    public function __construct(View $view, Auth $auth)
    {
        $this->view = $view;
        $this->auth = $auth;
    }

    /**
     * Show the registration page.
     *
     * @param   Psr\Http\Message\RequestInterface     $request
     * @return  Zend\Diactoros\Response
     */
    public function index(RequestInterface $request)
    {
        return $this->view->render('auth/register.twig');
    }

    /**
     * Handle the user registration.
     *
     * @param  RequestInterface $request
     * @return \Zend\Diactoros\Response\RedirectResponse
     */
    public function register(RequestInterface $request)
    {
        $data = $this->validateRegistration($request);
    }

    /**
     * Return the validation.
     *
     * @param  RequestInterface $request
     * @return [type]                    [description]
     */
    protected function validateRegistration(RequestInterface $request)
    {
        // Set the validation rules
        return $this->validate($request, [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', ['exists', User::class]],
            'password' => ['required', ['lengthMin', 6]],
            'password_confirm' => ['required', ['equals', 'password']],
        ]);
    }
}