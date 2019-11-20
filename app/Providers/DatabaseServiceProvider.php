<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Illuminate\Database\Capsule\Manager;

class DatabaseServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \League\Container\ServiceProvider\BootableServiceProviderInterface
     */
    public function boot()
    {
        $container = $this->getContainer();

        $config = $container->get('config');

        $capsule = new Manager();

        $capsule->addConnection(
            $config->get('db.' . env('DB_CONFIG_TYPE'))
        );

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
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
