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
     * Return the list of all the available commands.
     *
     * @return  array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
} 