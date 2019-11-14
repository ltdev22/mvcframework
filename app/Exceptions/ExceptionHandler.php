<?php

namespace App\Exceptions;

use Exception;
use ReflectionClass;
use App\Exceptions\ValidationFailedException;
use App\Session\SessionStoreInterface;

class ExceptionHandler
{
    /**
     * The exception that is thrown.
     *
     * @var object
     */
    protected $exception;

    /**
     * The session store type being injected within the exception
     *
     * @var object
     */
    protected $session;

    /**
     * Create a new instence.
     *
     * @param Exception $exception
     * @return void
     */
    public function __construct(Exception $exception, SessionStoreInterface $session)
    {
        $this->exception = $exception;
        $this->session = $session;
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
     * Return a default handle for the exception.
     *
     * @param  Exception $e
     */
    protected function unhandledException(Exception $e)
    {
        throw $e;
    }
}