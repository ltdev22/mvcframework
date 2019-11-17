<?php

return [
    'name' => env('APP_NAME'),
    'debug' => env('APP_DEBUG', false),

    // Register any serviceProviders here ...
    'providers' => [
        '\App\Providers\AppServiceProvider',
        '\App\Providers\DatabaseServiceProvider',
        '\App\Providers\AuthServiceProvider',
        '\App\Providers\CsrfServiceProvider',
        '\App\Providers\HashServiceProvider',
        '\App\Providers\SessionServiceProvider',
        '\App\Providers\FlashSessionServiceProvider',
        '\App\Providers\ViewServiceProvider',
        '\App\Providers\ViewShareServiceProvider',
    ],

    // Register any middleware here ...
    'middleware' => [
        'App\Middleware\Authenticate',
        'App\Middleware\ShareValidationErrors',
        'App\Middleware\ClearValidationErrors',
        'App\Middleware\CsrfGuard',
    ],
];