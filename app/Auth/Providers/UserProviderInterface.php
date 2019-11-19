<?php

namespace App\Auth\Providers;

use App\Models\User;

interface UserProviderInterface
{
    /**
     * Find the user by the ID.
     *
     * @param  int    $id
     */
    public function getById(int $id);

    /**
     * Find a user by his username creadential.
     * This could be either his email or his username.
     * By default its the email though.
     *
     * @param  string $username
     */
    public function getByUsername(string $username);

    /**
     * Find a user by his remember identifier.
     *
     * @param  string $identifier
     */
    public function getByRememberIdentifier(string $identifier);

    /**
     * Set a remember token for the user.
     *
     * @param User   $user
     * @param string $identifier
     * @param string $hash
     */
    public function setUserRememberToken(User $user, string $identifier, string $hash);

    /**
     * Update the user's password hash.
     *
     * @param  User     $user
     * @param  string   $hash
     */
    public function updateUserPasswordHash(User $user, string $hash);

    /**
     * Clear user's remeber token and remember identifier.
     *
     * @param  User     $user
     */
    public function clearUserRememberToken(User $user);
}