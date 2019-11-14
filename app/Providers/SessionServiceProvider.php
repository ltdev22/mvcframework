<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Session\SessionStoreInterface;
use App\Session\FileSession;

class SessionServiceProvider extends AbstractServiceProvider
{
    /**
     * Array holding everything this service provider provides to the container.
     *
     * @var array
     */
    protected $provides = [
        SessionStoreInterface::class,
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

        $container->share(SessionStoreInterface::class, function () {
            return new FileSession();
        });
    }
}
