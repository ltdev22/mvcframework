<?php

return [
    'name' => env('APP_NAME'),
    'debug' => env('APP_DEBUG', false),

    // Register any serviceProviders here ...
    'providers' => [
        '\App\Providers\AppServiceProvider',
        '\App\Providers\DatabaseServiceProvider',
        '\App\Providers\ViewServiceProvider',
    ],
];