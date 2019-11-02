<?php

namespace App\Config\Loaders;

use App\Config\Loaders\LoaderInterface;

class ArrayLoader implements LoaderInterface
{
    /**
     * Holds the list of files required to load in.
     *
     * @var this
     */
    protected $files;

    /**
     * Create a new ArrayLoader instance
     *
     * @param   array   $files
     * @return  void
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * Iterate through the array of files passed in, require them in and set them,
     * and return a parsed key => value pair array.
     *
     * @return array
     */
    public function parse(): array
    {
        $parsed = [];
        
        foreach ($this->files as $namespace => $path) {
            try {
                $parsed[$namespace] = require $path;
            } catch (\Exception $e) {}
        }
        
        return $parsed;
    }
}