<?php

namespace App\Console\Traits;

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
}