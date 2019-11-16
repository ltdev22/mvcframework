<?php

namespace App\Console\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait GeneratableTrait
{
    /**
     * The path to stubs directory
     *
     * @var string
     */
    protected $stubsDirectory = __DIR__ . '/../stubs';

    /**
     * Generate a new stub.
     *
     * @param  string $filename
     * @param  array  $replacements
     *
     * @return string
     */
    public function generateStub(string $filename, array $replacements)
    {
        return str_replace(
            array_keys($replacements),
            $replacements,
            file_get_contents($this->stubsDirectory . '/' . $filename . '.stub')
        );
    }

    /**
     * Start generating the file we want to create by setting
     * the target file path, the namespace and file name.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @param  string          $fileType
     * @return array
     */
    public function getGenerateBaseFormat(InputInterface $input, OutputInterface $output, string $fileType): array
    {
        // Grab the base file type and set namespacing
        $fileTypeBase = base_path('/app/'. $fileType);
        $fileTypePath = $fileTypeBase . '/';
        $namespace = 'App\\' . $fileType;

        // Extract each part of the file path and grab the fileType name
        $fileParts = explode('\\', $this->argument('name'));
        $fileName = array_pop($fileParts);

        // Clean file path
        $cleanPath = implode('/', $fileParts);

        // Do we have any sub directories we need to add?
        if (count($fileParts) >= 1) {
            $fileTypePath = $fileTypePath . $cleanPath;

            // Build the namespace
            $namespace = $namespace . '\\' . str_replace('/', '\\', $cleanPath);

            // Make directories
            if (!is_dir($fileTypePath)) {
                mkdir($fileTypePath, 0755, true);
            }
        }

        // Set the path we want to put the new fileType and
        // create the file if it doesn't already exist
        $target = $fileTypePath . '/' . $fileName . '.php';

        return [
            'target' => $target,
            'namespace' => $namespace,
            'fileName' => $fileName,
        ];
    }
}