<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Session\FlashSession;
use App\Session\SessionStoreInterface;

class FlashSessionServiceProvider extends AbstractServiceProvider
{
    /**
     * Array holding everything this service provider provides to the container.
     *
     * @var array
     */
    protected $provides = [
        FlashSession::class,
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

        $container->share(FlashSession::class, function () use ($container) {
            return new FlashSession(
                $container->get(SessionStoreInterface::class
            ));
        });
    }
}
