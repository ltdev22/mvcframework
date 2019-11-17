<?php

namespace App\Cookie;

class CookieJar
{
    protected $path = '/';
    protected $domain = null;
    protected $secure = false;
    protected $httpOnly = true;

    /**
     * Setting a new cookie
     *
     * @param  string   $name
     * @param  mixed    $value
     * @param  int      $minutes
     * @return void
     */
    public function set(string $name, $value, int $minutes = 60)
    {
        // Reset the expiry time in seconds
        $expiry = time() + ($minutes * 60);

        setcookie($name, $value, $expiry, $this->path, $this->domain, $this->secure, $this->httpOnly);
    }

    /**
     * Return a cookie.
     *
     * @param  string       $key
     * @param  mixed|null   $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if ($this->exists($key)) {
            return $_COOKIE[$key];
        }

        return $default;
    }

    /**
     * Have we already set this cookie?
     *
     * @param  string $key
     * @return boolean
     */
    public function exists(string $key): bool
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]);
    }

    /**
     * Clear a cookie.
     *
     * @param  string $key
     * @return void
     */
    public function clear(string $key)
    {
        $this->set($key, null, -2628000, $this->path, $this->domain);
    }

    /**
     * Set a cookie to last for really long time.
     *
     * @param  string   $key
     * @param  mixed    $value
     * @return void
     */
    public function forever(string $key, $value)
    {
        $this->set($key, $value, 2628000);
    }
}