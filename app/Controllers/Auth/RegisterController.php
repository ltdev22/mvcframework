<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use App\Utilities\View;
use App\Core\Auth\Auth;
use App\Core\Auth\Hashing\HasherInterface;
use App\Models\User;
use League\Route\Router;

class RegisterController extends Controller
{

    /**
     * The auth instance.
     *
     * @var \App\Core\Auth\Auth
     */
    protected $auth;

    /**
     * The hash instance.
     *
     * @var \App\Core\Auth\Hashing\HasherInterface
     */
    protected $hash;

    /**
     * The route instance.
     *
     * @var \League\Route\Router
     */
    protected $route;

    /**
     * The view instance injected in routes.php
     *
     * @var \App\Utilities\View
     */
    protected $view;

    /**
     * Instatiate the controller
     *
     * @param   \App\Utilities\View                     $view
     * @param   \App\Core\Auth\Hashing\HasherInterface       $hash
     * @param   \League\Route\Router                    $route
     * @param   \App\Core\Auth\Auth                          $auth
     * @return  void
     */
    public function __construct(
        View $view,
        HasherInterface $hash,
        Router $route,
        Auth $auth
    )
    {
        $this->view = $view;
        $this->hash = $hash;
        $this->route = $route;
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

        $user = $this->createUser($data);

        $this->loginAfterRegistering($data);

        return redirectTo($this->route->getNamedRoute('home')->getPath());
    }

    /**
     * Create a new user with the data we have available
     * after the validation.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function createUser(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $this->hash->create($data['password']),
        ]);
    }

    /**
     * Handle the registration validation.
     *
     * @param  RequestInterface $request
     * @return array
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

    /**
     * Login automatically the new user after the registration.
     *
     * @param  array  $data
     * @return array
     */
    protected function loginAfterRegistering(array $data)
    {
        $this->auth->attempt(
            $data['email'],
            $data['password']
        );

        return $this->loginAfterRegisteringExtend($data);
    }

    /**
     * Do any additional stuff after logging in.
     *
     * @param  array  $data
     * @return array
     */
    protected function loginAfterRegisteringExtend(array $data)
    {
        return $data;
    }
}