<?php

namespace App\Exceptions;

use Exception;
use ReflectionClass;
use App\Exceptions\ValidationFailedException;

class ExceptionHandler
{
    /**
     * The exception that is thrown.
     *
     * @var object
     */
    protected $exception;

    /**
     * Create a new instence.
     *
     * @param Exception $exception
     * @return void
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
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
        // @TODO Session set
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