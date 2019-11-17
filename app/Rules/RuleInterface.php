<?php

namespace App\Rules;

interface RuleInterface
{
    /**
     * Will do validation for any custom rule we create.
     * $field refers to the db field we want to check in the database
     * $value refers to the value we want to check
     * $params refers to an array of additional params (NOTE: this is not an assoc array)
     * $fields refers to an array of additional fields
     *
     * @param  string $field
     * @param  string $value
     * @param  array  $params
     * @param  array  $fields
     * @see  \App\Providers\ValidationRuleServiceProvider
     */
    public function validate(string $field, string $value, array $params, array $fields);
}