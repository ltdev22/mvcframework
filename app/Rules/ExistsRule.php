<?php

namespace App\Rules;

use App\Rules\RuleInterface;
use App\Models\User;

class ExistsRule implements RuleInterface
{
    /**
     * {@inheritdoc}
     *
     * @return boolean
     * @see  \App\Rules\RuleInterface
     */
    public function validate(string $field, string $value, array $params, array $fields): bool
    {
        // Query the database to see if there's already a record
        // with this specific $field => $value
       // and check if record has been found
        return User::where($field, $value)->first() === null;
    }
}