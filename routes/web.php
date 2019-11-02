<?php

/**
 * Web routes are set here
 *
 * Example would be:
 * - $route->get('/foo', 'App\Controllers\FooController::fooMethod')->setName('foo');
 *
 */

// $route->get('/', 'App\Controllers\HomeController::index')->setName('home');
$route->get('/', function($request) use ($container) {
    return (new \App\Controllers\HomeController($container->get(\App\Wrappers\View::class)))->index($request);
})->setName('home');
