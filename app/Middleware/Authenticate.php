<?php

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Auth\Auth;

class Authenticate implements MiddlewareInterface
{
    /**
     * The auth instance.
     *
     * @var \App\Auth\Auth
     */
    protected $auth;

    /**
     * Create new instance.
     *
     * @param  \App\Auth\Auth $auth
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
        // Persist the logged in user
        if ($this->auth->hasUserInSession()) {
            try {
                $this->auth->setUserFromSession();
            } catch (\Exception $e) {
                // @TODO $this->auth->logout();
            }
        }

        // Invoke the rest of the middleware stack and your controller resulting
        // in a returned response object
        $response = $handler->handle($request);

        return $response;
    }
}