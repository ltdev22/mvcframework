<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use App\Utilities\View;
use App\Auth\Hashing\HasherInterface;
use App\Models\User;
use League\Route\Router;
use Doctrine\ORM\EntityManager;

class RegisterController extends Controller
{
    /**
     * The db instance.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $db;

    /**
     * The hash instance.
     *
     * @var \App\Auth\Hashing\HasherInterface
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
     * @param   \App\Auth\Hashing\HasherInterface       $hash
     * @param   \League\Route\Router                    $route
     * @param   \Doctrine\ORM\EntityManager             $db
     * @return  void
     */
    public function __construct(
        View $view,
        HasherInterface $hash,
        Router $route,
        EntityManager $db
    )
    {
        $this->view = $view;
        $this->hash = $hash;
        $this->route = $route;
        $this->db = $db;
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
        $user = new User;

        $user->fill([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $this->hash->create($data['password']),
        ]);

        // This is where we store the user
        $this->db->persist($user);
        $this->db->flush();

        return $user;
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
}