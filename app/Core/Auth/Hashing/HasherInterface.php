<?php

namespace App\Core\Auth\Hashing;

interface HasherInterface
{
    /**
     * Create a password hash by taking the plain text password.
     *
     * @param  string $plainText
     */
    public function create(string $plainText);

    /**
     * Check if the plain password text matched a hash.
     *
     * @param  string $plainText
     * @param  string $hash
     */
    public function check(string $plainText, string $hash);

    /**
     * Check if a hash needs to be re-hashed.
     *
     * @param  string $hash
     */
    public function needsRehash(string $hash);
}