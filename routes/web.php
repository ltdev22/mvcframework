<?php

/**
 * Web routes are set here
 *
 * Example would be:
 * - $route->get('/foo', 'App\Controllers\FooController::fooMethod')->setName('foo');
 *
 */

$route->get('/', 'App\Controllers\HomeController::index')->setName('home');

/* Auth routes */
$route->group('/auth', function ($route) {

    /* Login */
    $route->get('/login', 'App\Controllers\Auth\LoginController::index')->setName('auth.login');
    $route->post('/login', 'App\Controllers\Auth\LoginController::login');

    /* Logout */
    $route->post('/logout', 'App\Controllers\Auth\LogoutController::logout')->setName('auth.logout');

    /* Register */
    $route->get('/register', 'App\Controllers\Auth\RegisterController::index')->setName('auth.register');
    $route->post('/register', 'App\Controllers\Auth\RegisterController::register');
});
