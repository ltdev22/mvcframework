<?php

namespace App\Core\Auth\Hashing;

use App\Core\Auth\Hashing\HasherInterface;

class BcryptHasher implements HasherInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(string $plainText): string
    {
        // Create a hash using the PHP Password Hashing API
        $hash = password_hash($plainText, PASSWORD_BCRYPT, $this->options());

        if (!$hash) {
            throw new RuntimeException('Bcrypt not supported.');
        }

        return $hash;
    }

    /**
     * {@inheritdoc}
     */
    public function check(string $plainText, string $hash): bool
    {
        return password_verify($plainText, $hash);
    }

    /**
     * {@inheritdoc}
     */
    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_BCRYPT, $this->options());
    }

    /**
     * Return the options we use within PHP Password Hashing API.
     *
     * @return array
     */
    protected function options(): array
    {
        return [
            'cost' => 12,
        ];
    }
}