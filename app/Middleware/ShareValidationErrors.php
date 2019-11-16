<?php

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Utilities\View;
use App\Session\SessionStoreInterface;

class ShareValidationErrors implements MiddlewareInterface
{
    /**
     * Hold the view as we will need to pass the validation
     * to the view again.
     *
     * @var \App\Utilities\View
     */
    protected $view;

    /**
     * Hold any session we have set.
     *
     * @var \App\Session\SessionStoreInterface
     */
    protected $session;

    /**
     * Create new instance
     *
     * @param \App\Utilities\View                    $view
     * @param \App\Session\SessionStoreInterface    $session
     */
    public function __construct(View $view, SessionStoreInterface $session)
    {
        $this->view = $view;
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
        // Share any validation errors and old data we might have within the view 
        // if the form validation failed
        $this->view->share([
            'errors' => $this->session->get('errors', []),
            'old' => $this->session->get('old', []),
        ]);

        // Invoke the rest of the middleware stack and your controller resulting
        // in a returned response object
        $response = $handler->handle($request);

        return $response;
    }
}