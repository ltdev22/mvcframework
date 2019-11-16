<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use App\Wrappers\View;

class ViewShareServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \League\Container\ServiceProvider\BootableServiceProviderInterface
     */
    public function boot()
    {
        $container = $this->getContainer();

        $container->get(View::class)->share([
            'config' => $container->get('config'),
        ]);
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
