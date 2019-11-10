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
    public function handle(InputInterface $input, OutputInterface$output)
    {
        // Grab the base model and set namespacing
        $modelBase = base_path('/app/Models');
        $modelPath = $modelBase . '/';
        $namespace = 'App\\Models';

        // Extract each part of the file path and grab the model name
        $fileParts = explode('\\', $this->argument('name'));
        $fileName = array_pop($fileParts);

        // Clean file path
        $cleanPath = implode('/', $fileParts);

        // Do we have any sub directories we need to add?
        if (count($fileParts) >= 1) {
            $modelPath = $modelPath . $cleanPath;
            
            // Build the namespace
            $namespace = $namespace . '\\' . str_replace('/', '\\', $cleanPath);

            // Make directories
            if (!is_dir($modelPath)) {
                mkdir($modelPath, 0755, true);
            }
        }

        // Set the path we want to put the new model and
        // create the file if it doesn't already exist
        $target = $modelPath . '/' . $fileName . '.php';

        if (file_exists($target)) {
            return $this->error('The model already exists.');
        }

        $stub = $this->generateStub('model', [
            'DummyNamespace' => $namespace,
            'DummyModel' => $fileName,
        ]);
        
        file_put_contents($target, $stub);

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