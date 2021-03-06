<?php

/**
 * Web routes are set here
 * A basic example would be: $route->get('/foo', 'App\Controllers\FooController::fooMethod')->setName('foo');
 *
 * @see https://route.thephpleague.com/4.x/routes/
 */

$route->get('/', 'App\Controllers\HomeController::index')->setName('home');

// Add here any routes for pages require authorization to access them
$route->group('', function ($route) {
    $route->get('/dashboard', 'App\Controllers\DashboardController::index')->setName('dashboard');

    /* Auth routes - Logout */
    $route->post('logout', 'App\Controllers\Auth\LogoutController::logout')->setName('auth.logout');

})->middleware($container->get(\App\Middleware\Authenticated::class));

$route->group('', function ($route) {

    /* Auth routes - there's no need for authorized users to access these pages */

    /* Login */
    $route->get('/login', 'App\Controllers\Auth\LoginController::index')->setName('auth.login');
    $route->post('/login', 'App\Controllers\Auth\LoginController::login');

    /* Register */
    $route->get('/register', 'App\Controllers\Auth\RegisterController::index')->setName('auth.register');
    $route->post('/register', 'App\Controllers\Auth\RegisterController::register');

})->middleware($container->get(\App\Middleware\Guest::class));
