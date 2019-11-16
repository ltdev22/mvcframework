<?php

namespace App\Session;

use App\Session\SessionStoreInterface;

class FlashSession
{
    /**
     * The session instance.
     * @var [type]
     */
    protected $session;

    /**
     * The cached messages.
     *
     * @var array
     */
    protected $flashMessages;

    /**
     * Create a new instance.
     *
     * @param SessionStoreInterface $session
     */
    public function __construct(SessionStoreInterface $session)
    {
        $this->session = $session;

        // Cache all messages before we clear these out
        $this->loadFlashMessagesIntoCache();

        $this->clear();
    }

    /**
     * Return a single flash by providing a key.
     *
     * @param  string $key
     * @return null|mixed
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->flashMessages[$key];
        }
        return null;
    }

    /**
     * Setting new flash data.
     *
     * @param  string $key
     * @param  mixed $value
     */
    public function now($key, $value)
    {
        $this->session->set('flash', array_merge(
            $this->session->get('flash') ?? [],
            [$key => $value]
        ));
    }

    /**
     * Do we have a specific flash?
     *
     * @param  string  $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        return isset($this->flashMessages[$key]);
    }

    /**
     * Get all flash data.
     *
     * @return null|array
     */
    protected function all(): ?array
    {
        return $this->session->get('flash');
    }

    /**
     * Cache all flash messages.
     */
    protected function loadFlashMessagesIntoCache()
    {
        $this->flashMessages = $this->all();
    }

    /**
     * Clear all flash messages.
     */
    protected function clear()
    {
        $this->session->clear('flash');
    }
}