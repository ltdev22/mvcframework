<?php

namespace App\Core\Utilities\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;
use League\Route\Router;

class PathExtension extends Twig_Extension
{
    /**
     * The Route instance
     *
     * @var \League\Route\Router
     */
    protected $route;

    /**
     * Create a new instence.
     *
     * @param Router $route
     * @return  void
     */
    public function __construct(Router $route)
    {
        $this->route = $route;
    }
    /**
     * We tell Twig which functions to expose.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            // so we will expose the 'route' function
            new Twig_SimpleFunction('route', [$this, 'route'])
        ];
    }

    /**
     * Return the path of the route when passing the route name.
     * This very usefull to use within the views in order to specify for example
     * link href attributes etc. Example could be: {{ route('foo.bar') }} and this
     * will return the path of ths route
     *
     * @param  string $name [description]
     * @return string
     * @see  web/routes.php
     */
    public function route(string $name): string
    {
        return $this->route->getNamedRoute($name)->getPath();
    }
}