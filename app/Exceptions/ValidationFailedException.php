<?php

namespace App\Exceptions;

use Exception;
use Psr\Http\Message\RequestInterface;

class ValidationFailedException extends Exception
{
    /**
     * Holds the request passed from the validation.
     *
     * @var object
     */
    protected $request;

    /**
     * The array of errors.
     *
     * @var array
     */
    protected $errors;

    /**
     * Create a new instance.
     *
     * @param \Psr\Http\Message\RequestInterface    $request
     * @param array                                 $errors
     *
     * @return  void
     */
    public function __construct(RequestInterface $request, array $errors)
    {
        $this->request = $request;
        $this->errors = $errors;
    }

    /**
     * Return the url we want to redirect if the validation fails.
     * This would be the url from which we submited the form and failed 
     * the validation, so we need to redirect back.
     *
     * @return [type] [description]
     */
    public function getBackPath()
    {
        return $this->request->getUri()->getPath();
    }

    /**
     * Return the old input we submited to the form and failed the validation.
     *
     * @return [type] [description]
     */
    public function getOldInput()
    {
        return $this->request->getParsedBody();
    }

    /**
     * Return the validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}