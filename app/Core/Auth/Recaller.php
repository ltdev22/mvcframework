<?php

namespace App\Core\Auth;

class Recaller
{
    protected $separator = '|';

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
        return $identifier . $this->separator . $token;
    }

    /**
     * Split the cookie value.
     *
     * @param  string $value
     * @return array
     */
    public function splitCookieValue(string $value): array
    {
        return explode($this->separator, $value);
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
     * Validate the token.
     *
     * @param  string $rawToken
     * @param  string $hashedToken
     * @return boolean
     */
    public function validateToken(string $rawToken, string $hashedToken): bool
    {
        return $this->getTokenHashed($rawToken) === $hashedToken;
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