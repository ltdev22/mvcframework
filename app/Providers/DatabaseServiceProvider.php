<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class DatabaseServiceProvider extends AbstractServiceProvider
{
    /**
     * Array holding everything this service provider provides to the container.
     *
     * @var array
     */
    protected $provides = [
        EntityManager::class,
    ];

    /**
     * {@inheritdoc}
     *
     * @see \League\Container\ServiceProvider\AbstractServiceProvider
     * @see \League\Container\ServiceProvider\ServiceProviderInterface
     */
    public function register()
    {     
        $container = $this->getContainer();

        $config = $container->get('config');

        $container->share(EntityManager::class, function () use ($config) {
            // Create a new EntityManager / Model to access the database
            // - since we have the db config we can pass this as 1st argument rather than typing each one here
            // - also as 2nd argumenet is a setup method, with which we can use meta data in the models
            //   to define the db table, the columns etc we 're trying to read from
            $entityManager = EntityManager::create(
                $config->get('db.' . env('DB_CONFIG_TYPE')),
                Setup::createAnnotationMetadataConfiguration(
                    [base_path('app')],
                    $config->get('app.debug')
                )
            );

            return $entityManager;
        });
    }
}
