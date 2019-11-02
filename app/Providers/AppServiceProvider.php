<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;

class AppServiceProvider extends AbstractServiceProvider
{
    /**
     * Array holding everything this service provider provides to the container.
     *
     * @var array
     */
    protected $provides = [
        'test',
    ];

    /**
     * {@inheritdoc}
     *
     * @see \League\Container\ServiceProvider\AbstractServiceProvider
     * @see \League\Container\ServiceProvider\ServiceProviderInterface
     */
    public function register()
    {
        // Now we can share anything on our container so we can access them
        // anywhere we inject things in
        
        $container = $this->getContainer();

        $container->share('test', function () {
            return 'Hello World!';
        });
    }
}
