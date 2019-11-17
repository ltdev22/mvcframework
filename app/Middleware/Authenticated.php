<?php

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Auth\Auth;

class Authenticated implements MiddlewareInterface
{
    /**
     * The auth instence
     *
     * @var \App\Auth\Auth
     */
    protected $auth;

    /**
     * Create a new instance
     *
     * @param \App\Auth\Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * {@inheritdoc}
     *
     * @see  \Psr\Http\Server\MiddlewareInterface
     * @see  https://route.thephpleague.com/4.x/middleware/
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Invoke the rest of the middleware stack and your controller resulting
        // in a returned response object
        $response = $handler->handle($request);

        // If the user is not sigend in we wan't to redirect him from the
        // pages require authorization to access
        if (!$this->auth->check()) {
            $response = redirectTo('/');
        }

        return $response;
    }
}