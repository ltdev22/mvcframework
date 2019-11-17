<?php

namespace App\Console\Commands\Generators;

use App\Console\Command;
use App\Console\Traits\GeneratableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExceptionGeneratorCommand extends Command
{
    use GeneratableTrait;

    /**
     * The name of the command.
     *
     * @param string
     */
    protected $command = 'make:exception';

    /**
     * The description of the command.
     *
     * @param string
     */
    protected $description = 'Generate a new exception command.';

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
        $stub = $this->generateStub('exception', [
            'DummyException' => $this->argument('name'),
        ]);

        // Find where we want to place the new file and
        // create the new file if it doesn't already exist
        $target = base_path('/app/Exceptions/' . $this->argument('name') . '.php');

        if (file_exists($target)) {
            return $this->error('The exception already exists.');
        }

        file_put_contents($target, $stub);

        return $this->info('The exception has been generated.');
    }

    /**
     * Command arguments.
     *
     * @return array
     */
    protected function arguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the exception to generate.'],
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