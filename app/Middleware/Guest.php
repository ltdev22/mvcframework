<?php

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Core\Auth\Auth;

class Guest implements MiddlewareInterface
{
    /**
     * The auth instence
     *
     * @var \App\Core\Auth\Auth
     */
    protected $auth;

    /**
     * Create a new instance
     *
     * @param \App\Core\Auth\Auth $auth
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

        // If the user is sigend redirect him
        if ($this->auth->check()) {
            $response = redirectTo('/');
        }

        return $response;
    }
}