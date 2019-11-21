<?php

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Core\Auth\Auth;

class AuthenticateFromCookie implements MiddlewareInterface
{
    /**
     * The auth instance.
     *
     * @var \App\Core\Auth\Auth
     */
    protected $auth;

    /**
     * Create new instance.
     *
     * @param  \App\Core\Auth\Auth $auth
     * @return  void
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
        // If the user is already signed in, return the next callable middleware
        if ($this->auth->check()) {
            return $handler->handle($request);
        }

        // Does the user have a cookie set?
        if ($this->auth->hasRecaller()) {
            try {
                $this->auth->setUserFromCookie();
            } catch (\Exception $e) {
                $this->auth->logout();
            }
        }
        // Invoke the rest of the middleware stack and your controller resulting
        // in a returned response object
        $response = $handler->handle($request);

        return $response;
    }
}