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
     * Configure the command by setting
     * - the command name,
     * - the description,
     * - any optional arguments & additional options
     * 
     * @return void
     */
    protected function configure()
    {
        $this->setName($this->command)->setDescription($this->description);

        $this->addArguments();
        $this->addOptions();
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface      $input
     * @param  \Symfony\Component\Console\Output\OutputInterface    $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setInputOutput($input, $output);

        return $this->handle($input, $output);
    }

    /**
     * Return a specific argument.
     *
     * @param  string $name
     */
    protected function argument($name)
    {
        return $this->input->getArgument($name);
    }

    /**
     * Return a specific option.
     *
     * @param  string $name
     */
    protected function option($name)
    {
        return $this->input->getOption($name);
    }

    /**
     * Add any arguments we want to pass to the command when typing through the console.
     *
     * @return  void
     */
    protected function addArguments()
    {
        // Iterate through the array of arguments and use Symfony's addArgument
        foreach ($this->arguments() as $argument) {
            $this->addArgument($argument[0], $argument[1], $argument[2]);
        }
    }

    /**
     * Add any options we want to pass to the command when typing through the console.
     *
     * @return  void
     */
    protected function addOptions()
    {
        // Iterate through the array of options and use Symfony's addOption
        foreach ($this->options() as $option) {
            $this->addOption($option[0], $option[1], $option[2], $option[3], $option[4]);
        }
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
