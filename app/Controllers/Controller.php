<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Valitron\Validator;
use App\Exceptions\ValidationFailedException;

abstract class Controller
{
    /**
     * Handle form validation.
     *
     * @param  \Psr\Http\Message\RequestInterface    $request
     * @param  array                                 $rules
     *
     * @throws \App\Exceptions\ValidationFailedException
     * @return array
     */
    public function validate(RequestInterface $request, array $rules)
    {
        $validator = new Validator($request->getParsedBody());

        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            // Ooops, the validation has failed :(
            throw new ValidationFailedException($request, $validator->errors());
        }

        // Return the data we used to validate so we can carry on
        return $request->getParsedBody();
    }
}