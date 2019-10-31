<?php

// Start the session.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load any dependencies required for the framework to run.
require_once __DIR__ . '/../vendor/autoload.php';

// Try and load the env variables.
try {
    $dotenv = \Dotenv\Dotenv::create(__DIR__ . '/..//');
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // If we can't find the .env file and can't read any ENV variable,
    // then we can't see any of the configuration, so this is safe to stay empty.
}
