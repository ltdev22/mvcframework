<?php

// Start the session.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load any dependencies required for the framework to run.
require_once __DIR__ . '/../vendor/autoload.php';

// Try and load the env variables.
try {
    $dotenv = \Dotenv\Dotenv::create(base_path());
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // We don't need to do anything here actually if we can't find the .env file
    // or can't read any ENV variables, as we can't see any of the configuration
}

// Load the container
require_once base_path('/bootstrap/container.php');

// Get the routes (route collection) out of the container
// Then we need to load the routes first before the request and the response are dispatched
// Finally we can dispatch the route along with both the request and the response
$route = $container->get(\League\Route\Router::class);

require_once base_path('/bootstrap/middleware.php');
require_once base_path('/routes/web.php');

// Dispatch the request. If by any reason fails we will catch every exception
// we throw within the entire framework here
try {
    $response = $route->dispatch($container->get('request'));
} catch (\Exception $e) {
    $handler = new \App\Core\Exceptions\ExceptionHandler(
        $e,
        $container->get(\App\Core\Session\SessionStoreInterface::class),
        $container->get(\App\Utilities\View::class)
    );

    $response = $handler->respond();
}
