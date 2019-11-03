<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand
{
    /**
     * Hold the input and output intefraces. These are private properties as
     * we don't want to access them directly in any way within each of the commands.
     */
    private $input;
    private $outut;

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

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface      $input
     * @param  \Symfony\Component\Console\Output\OutputInterface    $output
     * @return [type]        [description]
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setInputOutput($input, $output);
        return $this->handle($input, $output);
    }

    /**
     * Helper output function returning the output wrapped within info tags.
     *
     * @param  string $value
     */
    protected function info($value = '')
    {
        return $this->output->writeln('<info>' . $value . '</info>');
    }

    /**
     * Helper output function returning the output wrapped within error tags.
     *
     * @param  string $value
     */
    protected function error($value = '')
    {
        return $this->output->writeln('<error>' . $value . '</error>');
    }

    /**
     * Define the input and output interfaces within the command instance.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface      $input
     * @param  \Symfony\Component\Console\Output\OutputInterface    $output
     * @return void
     */
    private function setInputOutput(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }
}
