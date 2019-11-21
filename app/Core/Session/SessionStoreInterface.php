<?php

namespace App\Core\Session;

interface SessionStoreInterface
{
    /**
     * Set something into session.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     */
    public function set($key, $value = null);

    /**
     * Get something out of the session.
     *
     * @param  mixed  $key
     * @param  mixed  $default
     */
    public function get($key, $default = null);

    /**
     * Does the requested key exists in the session?
     *
     * @param  mixed $key
     */
    public function exists($key);

    /**
     * Delete something from the session. Could be a list of things.
     *
     * @param  mixed $key
     */
    public function clear(...$key);
}