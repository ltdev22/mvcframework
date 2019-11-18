<?php

namespace App\Auth;

class Recaller
{
    /**
     * Generate the value that we will insert into the cookie.
     *
     * @return array
     */
    public function generate(): array
    {
        return [
            $this->generateIdentifier(),
            $this->generateToken(),
        ];
    }

    /**
     * Generate the value we are going to store to the 'remember me' cookie in auth.
     *
     * @param  string $identifier
     * @param  string $token
     * @return string
     */
    public function generateValueForCookie(string $identifier, string $token): string
    {
        return $identifier . '|' . $token;
    }

    /**
     * Return the token hashed ready for storing in the database.
     *
     * @param  string $token
     * @return string
     */
    public function getTokenHashed(string $token): string
    {
        return hash('sha256', $token);
    }

    /**
     * Generate a unique identifier.
     *
     * @return string
     */
    protected function generateIdentifier(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Generate a unique token.
     *
     * @return string
     */
    protected function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}