<?php

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Session\SessionStoreInterface;

class ClearValidationErrors implements MiddlewareInterface
{
    /**
     * Hold any session we have set.
     *
     * @var \App\Session\SessionStoreInterface
     */
    protected $session;

    /**
     * Create new instance
     *
     * @param \App\Session\SessionStoreInterface    $session
     */
    public function __construct(SessionStoreInterface $session)
    {
        $this->session = $session;
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

        // Clear any validation errors and old data
        $this->session->clear('errors', 'old');

        return $response;
    }
}