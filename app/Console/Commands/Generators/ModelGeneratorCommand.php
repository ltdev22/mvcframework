<?php

namespace App\Console\Commands\Generators;

use App\Console\Command;
use App\Console\Traits\GeneratableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModelGeneratorCommand extends Command
{
    use GeneratableTrait;

    /**
     * The name of the command.
     *
     * @param string
     */
    protected $command = 'make:model';

    /**
     * The description of the command.
     *
     * @param string
     */
    protected $description = 'Generate a new model command.';

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
        $ret = $this->getGenerateBaseFormat($input, $output, 'Models');

        if (file_exists($ret['target'])) {
            return $this->error('The model already exists.');
        }

        $stub = $this->generateStub('model', [
            'DummyNamespace' => $ret['namespace'],
            'DummyModel' => $ret['fileName'],
        ]);
        
        file_put_contents($ret['target'], $stub);

        return $this->info('The model has been generated.');
    }

    /**
     * Command arguments.
     *
     * @return array
     */
    protected function arguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the model to generate.'],
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