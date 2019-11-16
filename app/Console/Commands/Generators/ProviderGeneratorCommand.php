<?php

namespace App\Console\Commands\Generators;

use App\Console\Command;
use App\Console\Traits\GeneratableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProviderGeneratorCommand extends Command
{
    use GeneratableTrait;

    /**
     * The name of the command.
     *
     * @param string
     */
    protected $command = 'make:provider';

    /**
     * The description of the command.
     *
     * @param string
     */
    protected $description = 'Generate a new provider command.';

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
        // Generate the stub
        $stub = $this->generateStub('provider', [
            'DummyClass' => $this->argument('name'),
        ]);

        // Find where we want to place the new file and
        // create the new file if it doesn't already exist
        $target = base_path('/app/Providers/' . $this->argument('name') . '.php');

        if (file_exists($target)) {
            return $this->error('The provider already exists.');
        }

        file_put_contents($target, $stub);

        return $this->info('The provider has been generated.');
    }

    /**
     * Command arguments.
     *
     * @return array
     */
    protected function arguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the provider to generate.'],
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