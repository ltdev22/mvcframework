<?php

namespace App\Console\Commands\Generators;

use App\Console\Command;
use App\Console\Traits\GeneratableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerGeneratorCommand extends Command
{
    use GeneratableTrait;

    /**
     * The name of the command.
     *
     * @param string
     */
    protected $command = 'make:controller';

    /**
     * The description of the command.
     *
     * @param string
     */
    protected $description = 'Generate a new controller command.';

    /**
     * Handle the execution of the command.
     * 
     * @param  Symfony\Component\Console\Input\InputInterface       $input
     * @param  Symfony\Component\Console\Output\OutputInterface     $output
     * 
     * @return void
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $ret = $this->getGenerateBaseFormat($input, $output, 'Controllers');

        if (file_exists($ret['target'])) {
            return $this->error('The controller already exists.');
        }

        $stub = $this->generateStub('controller', [
            'DummyNamespace' => $ret['namespace'],
            'DummyController' => $ret['fileName'],
        ]);
        
        file_put_contents($ret['target'], $stub);

        return $this->info('The controller has been generated.');
    }

    /**
     * Command arguments.
     *
     * @return array
     */
    protected function arguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller to generate.'],
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