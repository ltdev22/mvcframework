<?php

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load any dependencies required for the framework to run
require_once __DIR__ . '/../vendor/autoload.php';
