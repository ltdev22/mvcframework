<?php

/**
 * Web routes are set here
 *
 * Example would be:
 * - $route->get('/foo', 'App\Controllers\FooController::fooMethod')->setName('foo');
 *
 */

$route->get('/', 'App\Controllers\HomeController::index')->setName('home');
