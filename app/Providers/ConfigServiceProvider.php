<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Config\Loaders\ArrayLoader;
use App\Config\Config;

class ConfigServiceProvider extends AbstractServiceProvider
{
    /**
     * Array holding everything this service provider provides to the container.
     *
     * @var array
     */
    protected $provides = [
        'config',
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

        $container->share('config', function () {
            $loader = new ArrayLoader([
                'app' => base_path('config/app.php'),
                'cache' => base_path('config/cache.php'),
                'db' => base_path('config/db.php'),
            ]);
            return (new Config)->load([$loader]);
        });
    }
}
