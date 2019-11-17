<?php

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Security\Csrf;
use App\Exceptions\CsrfTokenException;

class CsrfGuard implements MiddlewareInterface
{
    /**
     * The csrf instance
     *
     * @var \App\Security\Csrf
     */
    protected $csrf;

    /**
     * Create new instance.
     *
     * @param \App\Security\Csrf $csrf
     */
    public function __construct(Csrf $csrf)
    {
        $this->csrf = $csrf;
    }

    /**
     * {@inheritdoc}
     *
     * @see  \Psr\Http\Server\MiddlewareInterface
     * @see  https://route.thephpleague.com/4.x/middleware/
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->requestRequiresProtection($request)) {
            return $handler->handle($request);
        }

        if (!$this->csrf->tokenIsValid($this->getTokenFromRequest($request))) {
            throw new CsrfTokenException();
        }

        // Invoke the rest of the middleware stack and your controller resulting
        // in a returned response object
        $response = $handler->handle($request);

        return $response;
    }

    /**
     * Return the token from the request made.
     *
     * @param  ServerRequestInterface $request
     * @return null|string
     */
    protected function getTokenFromRequest(ServerRequestInterface $request): ?string
    {
        return $request->getParsedBody()[$this->csrf->key()] ?? null;
    }

    /**
     * Does the request needs csrf protection?
     *
     * @param  ServerRequestInterface $request
     * @return boolean
     */
    protected function requestRequiresProtection(ServerRequestInterface $request): bool
    {
        return in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE']);
    }
}