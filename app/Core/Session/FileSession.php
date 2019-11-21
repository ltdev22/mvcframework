<?php

namespace App\Core\Session;

use App\Core\Session\SessionStoreInterface;

class FileSession implements SessionStoreInterface
{
    /**
     * {@inheritdoc}
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $sessionKey => $sessionValue) {
                $_SESSION[$sessionKey] = $sessionValue;
            }

            return;
        }

        $_SESSION[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        if ($this->exists($key)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return isset($_SESSION[$key]) && !empty($_SESSION[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(...$key)
    {
        foreach ($key as $sessionKey) {
            unset($_SESSION[$sessionKey]);
        }
    }
}