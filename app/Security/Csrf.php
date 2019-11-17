<?php

namespace App\Security;

use App\Session\SessionStoreInterface;

class Csrf
{
    /**
     * The session instance.
     *
     * @var \App\Session\SessionStoreInterface
     */
    protected $session;

    /**
     * Will we persist the token?
     *
     * @var boolean
     */
    protected $persistToken = true;

    public function __construct(SessionStoreInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Return the csrf key.
     *
     * @return string
     */
    public function key(): string
    {
        return '_token';
    }

    /**
     * Return the csrf token.
     *
     * @return string
     */
    public function token(): string
    {
        if (!$this->tokenNeedsToBeGenerated()) {
            return $this->getTokenFromSession();
        }

        // Generate a token, store it in session and return it.
        $this->session->set(
            $this->key(),
            $token = bin2hex(random_bytes(32))
        );

        return $token;
    }

    /**
     * Return the csrf token stored in session.
     *
     * @return string
     */
    protected function getTokenFromSession(): string
    {
        return $this->session->get($this->key());
    }

    /**
     * Do we need to generate a token?
     *
     * @return boolean
     */
    protected function tokenNeedsToBeGenerated(): bool
    {
        if (!$this->session->exists($this->key())) {
            return true;
        }

        if ($this->shouldPersistToken()) {
            return false;
        }

        return $this->session->exists($this->key());
    }

    /**
     * Should we persist token?
     *
     * @return boolean
     */
    protected function shouldPersistToken(): bool
    {
        return $this->persistToken;
    }
}