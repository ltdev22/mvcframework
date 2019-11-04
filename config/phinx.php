<?php
/**
 * The configuration settings for running Phinx migrations are listed here.
 * You can have a list of different settings depending on the environemnt the framework is running.
 * Most of the settings should be the same as in config/db.php
 */

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/../database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/../database/seeds',
    ],
    'environments' => [
        'default_migration_table' => env('DB_MIGRATIONS_TABLE', 'phinxlog'),
        'default_database' => env('DB_MIGRATIONS_DEFAULT_ENV', 'production'),
        'production' => [
            'adapter' => env('DB_MIGRATIONS_DRIVER', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'name' => env('DB_DATABASE', 'database'),
            'user' => env('DB_USERNAME', 'root'),
            'pass' => env('DB_PASSWORD', ''),
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => env('DB_MIGRATIONS_DRIVER', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'name' => env('DB_DATABASE', 'database'),
            'user' => env('DB_USERNAME', 'root'),
            'pass' => env('DB_PASSWORD', ''),
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => env('DB_MIGRATIONS_DRIVER', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'name' => env('DB_DATABASE', 'database'),
            'user' => env('DB_USERNAME', 'root'),
            'pass' => env('DB_PASSWORD', ''),
            'port' => '3306',
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation'
];