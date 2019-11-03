<?php

namespace App\Console;

use Symfony\Component\Console\Application;
use App\Console\Kernel;

class Console extends Application
{
    /** Holds the framework app. */
    protected $framework;
    
    /**
     * Create a new Console instance
     *
     * @param   $this 
     * @return  void
     */
    public function __construct($framework)
    {
        parent::__construct();
        $this->framework = $framework;
    }
    
    /**
     * Add all the Kernel commands to Symfony Console 
     * in order to list them in the command line.
     * 
     * @param  $kernel \Mvc\Console\Kernel
     */
    public function boot(Kernel $kernel)
    {
        // Create a new instance for each of the command
        foreach ($kernel->getCommands() as $command) {
            $this->add(new $command(
                $this->getFramework()
            ));
        }
    }
    
    /**
     * Helper function to return the framework instance.
     * 
     * @return $this
     */
    protected function getFramework()
    {
        return $this->framework;
    }
}