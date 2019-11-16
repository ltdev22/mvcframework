<?php

namespace App\Console;

class Kernel
{
    /**
     * Array holding all the commands we want to run.
     *
     * @param  array
     */
    protected $commands = [
        '\App\Console\Commands\CmderCommand',
    ];

    /**
     * Array holding all default/base commands.
     *
     * @var array
     */
    protected $defaultCommands = [
        '\App\Console\Commands\Generators\ConsoleGeneratorCommand',
        '\App\Console\Commands\Generators\ControllerGeneratorCommand',
        '\App\Console\Commands\Generators\ProviderGeneratorCommand',
        '\App\Console\Commands\Generators\ModelGeneratorCommand',
        '\App\Console\Commands\Generators\MiddlewareGeneratorCommand',
        '\App\Console\Commands\Generators\Migrations\CreateMigrationCommand',
        '\App\Console\Commands\Generators\Migrations\RunMigrationCommand',
        '\App\Console\Commands\Generators\Migrations\RollbackMigrationCommand',
        '\App\Console\Commands\Generators\Migrations\StatusMigrationCommand',
        '\App\Console\Commands\Generators\Migrations\CreateSeedCommand',
        '\App\Console\Commands\Generators\Migrations\RunSeedCommand',
    ];
    
    /**
     * Return the list of all the available commands.
     *
     * @return  array
     */
    public function getCommands(): array
    {
        return array_merge($this->commands, $this->defaultCommands);
    }
} 