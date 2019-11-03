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