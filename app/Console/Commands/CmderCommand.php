<?php
/**
 * This is used as a guide for implementing more commands,
 * it's not doing actually anything useful for the framework.
 */
namespace App\Console\Commands;

use App\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CmderCommand extends Command
{
    /**
     * The name of the command.
     * 
     * @param string
     */
    protected $command = 'test:cmder';
    
    /**
     * The description of the command.
     * 
     * @param string
     */
    protected $description = 'Check if Cmder is running properly.';
    
    /**
     * Handle the execution of the command.
     *
     * @param  Symfony\Component\Console\Input\InputInterface       $input
     * @param  Symfony\Component\Console\Output\OutputInterface     $output
     * 
     * @return void
     */
    public function handle(InputInterface $input, OutputInterface$output)
    {
        $this->info('The Cmder is up and running.');
    }
    
    /**
     * Command arguments.
     *
     * @return array
     */
    protected function arguments(): array
    {
        return [
            // 
        ];
    }
    
    /**
     * Command options.
     *
     * @return array
     */
    protected function options(): array
    {
        return [
            // 
        ];
    }
} 