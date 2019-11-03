<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Environment as Twig;
use App\Wrappers\View;

class ViewServiceProvider extends AbstractServiceProvider
{
    /**
     * Array holding everything this service provider provides to the container.
     *
     * @var array
     */
    protected $provides = [
        View::class,
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

        // Create a wrapper view class to inject into the controllers
        $container->share(View::class, function () use ($config) {

            // Set up Twig as per documentation 
            // @see https://twig.symfony.com/doc/2.x/api.html
            $loader = new FilesystemLoader(base_path('views'));

            $twig = new Twig($loader, [
                'cache' => $config->get('cache.views.path'),
                'debug' => $config->get('app.debug'),
            ]);

            // Add debug extension only if debuggin is enabled
            if ($config->get('app.debug')) {
                $twig->addExtension(new DebugExtension());
            }

            return new View($twig);
        });
    }
}
