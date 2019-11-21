<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Core\Auth\Auth;
use App\Core\Auth\Recaller;
use App\Core\Auth\Hashing\HasherInterface;
use App\Session\SessionStoreInterface;
use App\Core\Cookie\CookieJar;

class AuthServiceProvider extends AbstractServiceProvider
{
    /**
     * Array holding everything this service provider provides to the container.
     *
     * @var array
     */
    protected $provides = [
        Auth::class,
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

        $container->share(Auth::class, function () use ($container) {
            return new Auth(
                $container->get(HasherInterface::class),
                $container->get(SessionStoreInterface::class),
                new Recaller(),
                $container->get(CookieJar::class)
            );
        });
    }
}
