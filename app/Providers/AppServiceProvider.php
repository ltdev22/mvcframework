<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

class AppServiceProvider extends AbstractServiceProvider
{
    /**
     * Array holding everything this service provider provides to the container.
     *
     * @var array
     */
    protected $provides = [
        Router::class,
        'response',
        'request',
        'emitter',
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

        // Register the routes within the service provider
        $container->share(Router::class, function () use ($container) {
            $strategy = (new ApplicationStrategy())->setContainer($container);
            return (new Router)->setStrategy($strategy);
        });

        // Share the response and the request thats going to be outputed on the page
        $container->share('response', Response::class);

        $container->share('request', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });

        // Finally we can share the emitter to emit this response
        $container->share('emitter', function () {
            return new SapiEmitter;
        });
    }
}
