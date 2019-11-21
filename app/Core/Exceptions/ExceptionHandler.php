<?php

namespace App\Core\Exceptions;

use Exception;
use ReflectionClass;
use App\Core\Exceptions\ValidationFailedException;
use App\Core\Exceptions\CsrfTokenException;
use App\Core\Session\SessionStoreInterface;
use App\Core\Utilities\View;
use Psr\Http\Message\ResponseInterface;

class ExceptionHandler
{
    /**
     * The exception that is thrown.
     *
     * @var \Exception
     */
    protected $exception;

    /**
     * The session store type being injected within the exception
     *
     * @var \App\Core\Session\SessionStoreInterface
     */
    protected $session;

    /**
     * The view instance.
     *
     * @var \App\Core\Utilities\View
     */
    protected $view;

    /**
     * Create a new instence.
     *
     * @param Exception                 $exception
     * @param SessionStoreInterface     $session
     * @param View                      $view
     * @return void
     */
    public function __construct(Exception $exception, SessionStoreInterface $session, View $view)
    {
        $this->exception = $exception;
        $this->session = $session;
        $this->view = $view;
    }

    /**
     * Return a response from the thrown exception.
     *
     * @return $this
     */
    public function respond()
    {
        // Use the PHP Reflection API to get the class name of the thrown exception.
        // Then if we have a handle* method for this exception, we will return this
        // as a response for the handler.
        $class = (new ReflectionClass($this->exception))->getShortName();

        if (method_exists($this, $method = 'handle'.$class)) {
            return $this->{$method}($this->exception);
        }

        return $this->unhandledException($this->exception);
    }

    /**
     * Handle the ValidationFailedException.
     *
     * @param  ValidationFailedException $e
     * @return \Zend\Diactoros\Response\RedirectResponse 
     */
    protected function handleValidationFailedException(ValidationFailedException $e)
    {
        // Session set
        $this->session->set([
            'old' => $e->getOldInput(),
            'errors' => $e->getErrors(),
        ]);

        // Redirect the user back
        return redirectTo($e->getBack());
    }

    /**
     * Handle the CsrfTokenException.
     *
     * @param  CsrfTokenException $e
     * @return \Zend\Diactoros\Response\RedirectResponse
     */
    protected function handleCsrfTokenException(CsrfTokenException $e)
    {
        return $this->view->render('errors/csrf.twig');
    }

    /**
     * Return a default handle for the exception.
     *
     * @param  Exception $e
     */
    protected function unhandledException(Exception $e)
    {
        throw $e;
    }
}