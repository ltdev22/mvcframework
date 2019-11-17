<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Valitron\Validator;
use App\Rules\ExistsRule;
use Doctrine\ORM\EntityManager;

class ValidationRuleServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \League\Container\ServiceProvider\BootableServiceProviderInterface
     */
    public function boot()
    {
        /**
         * The Validator::addRule takes 3 arguments:
         * - the name of the new validation rule
         * - a closure where we boot the new rule we create within \App\Rules
         * - the message we want to output
         *
         *
         * We set a new validation rule called 'exists' to check if a record already exists
         * in the database. The signature of this callback will be
         * $field : as the db field we check
         * $value : the value we give when submitting a form
         * $params : any additional params
         * $fields : additional fields
         */
        Validator::addRule('exists', function ($field, $value, $params, $fields) {
            $rule = new ExistsRule(
                $this->getContainer()->get(EntityManager::class)
            );

            return $rule->validate($field, $value, $params, $fields);

        }, 'is already in use.');
    }

    /**
     * {@inheritdoc}
     *
     * @see \League\Container\ServiceProvider\AbstractServiceProvider
     * @see \League\Container\ServiceProvider\ServiceProviderInterface
     */
    public function register()
    {
        //
    }
}
