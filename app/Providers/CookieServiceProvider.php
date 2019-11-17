<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Cookie\CookieJar;

class CookieServiceProvider extends AbstractServiceProvider
{
    /**
     * Array holding everything this service provider provides to the container.
     *
     * @var array
     */
    protected $provides = [
        CookieJar::class,
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

        $container->share(CookieJar::class, function () {
            return new CookieJar();
        });
    }
}
