<?php

namespace App\Console\Commands;

use App\Console\Command;

class CmderCommand extends Command
{
    /**
     * The name of the command.
     * 
     * @param string
     */
    protected $command = 'cmder';
    
    /**
     * The description of the command.
     * 
     * @param string
     */
    protected $description = 'This is command is used to check if cmder is running properly.';
    
    /**
     * Handle the execution of the command.
     * 
     * @return [type] [description]
     */
    public function handle()
    {
        echo "Hello World!\n";
    }
    
    /**
     * Return an array of arguments.
     *
     * @return array
     */
    protected function arguments(): array
    {
        return [];
    }
    
    /**
     * Return an array of options.
     *
     * @return array
     */
    protected function options(): array
    {
        return [];
    }
} 