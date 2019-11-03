<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

abstract class Command extends SymfonyCommand
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Configure the command, i.e. set name, description, arguments, options etc.
     * 
     * @return void
     */
    protected function configure()
    {
        $this->setName($this->command)->setDescription($this->description);
    }
}
