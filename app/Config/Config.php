<?php

namespace App\Config;

use \App\Config\Loaders\LoaderInterface;

class Config
{
    /**
     * The main config array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Array holding the cache items.
     *
     * @var array
     */
    protected $cache = [];

    /**
     * Iterate through a list of loaders, parse each one and set it to the config array.
     *
     * @param  array  $loaders [description]
     * @return $this
     */
    public function load(array $loaders)
    {
        foreach ($loaders as $loader) {
            // Skip to the next array item if its not an instance of Loader
            if (!$loader instanceof LoaderInterface) {
                continue;
            }
            $this->config = array_merge($this->config, $loader->parse());
        }
        
        return $this;
    }

    /**
     * Get the value from a config setting by passing the key indexes
     * using a dot notation, for example $config->get('app.name')
     *
     * @param  string   $key
     * @param  mixed    $default
     * @return string
     */
    public function get($key, $default = null)
    {
        // Return the value from cache if we have it already
        if ($this->existsInCache($key)) {
            return $this->fromCache($key);
        }

        // Otherwise get it from the config (and also keep it in cache)
        // or return the default value being set if not found at all
        return $this->addToCache(
            $key,
            $this->extractFromConfig($key) ?? $default
        );
    }

    /**
     * Get the value from a config key.
     *
     * @param  string $key
     * @return string
     */
    protected function extractFromConfig($key)
    {
        $filtered = $this->config;
        // Iterate through the keys, if the key exists within the config
        // then further filter down
        foreach (explode('.', $key) as $segment) {
            if ($this->exists($filtered, $segment)) {
                $filtered = $filtered[$segment];
                continue;
            }
            return;
        }

        return $filtered;
    }

    /**
     * Does the key already exists in cache?
     *
     * @param  string $key
     * @return bool
     */
    protected function existsInCache(string $key): bool
    {
        return isset($this->cache[$key]);
    }
 
    /**
     * Return the value of the key from cache.
     *
     * @param  string $key
     * @return string
     */
    protected function fromCache(string $key): string
    {
        return (string) $this->cache[$key];
    }

    /**
     * Do we have this key in the config?
     *
     * @param  array  $config
     * @param  mixed  $key
     * @return bool
     */
    protected function exists(array $config, $key): bool
    {
        return array_key_exists($key, $config);
    }

    /**
     * Cache out of a particular key the value.
     *
     * @param  string $key
     * @param  string $value
     * @return string
     */
    protected function addToCache(string $key, string $value): string
    {
        $this->cache[$key] = $value;
        return $value;
    }
}