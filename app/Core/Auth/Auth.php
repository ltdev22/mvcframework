<?php

namespace App\Core\Auth;

use App\Core\Auth\Recaller;
use App\Models\User;
use App\Core\Auth\Hashing\HasherInterface;
use App\Core\Session\SessionStoreInterface;
use App\Core\Cookie\CookieJar;

class Auth
{

    /**
     * The hash instance.
     *
     * @var \App\Core\Auth\Hashing\HasherInterface
     */
    protected $hash;

    /**
     * The session instance.
     *
     * @var \App\Core\Session\SessionStoreInterface
     */
    protected $session;

    /**
     * The cookie instance.
     *
     * @var \App\Core\Cookie\CookieJar
     */
    protected $cookie;

    /**
     * The recaller instance.
     *
     * @var \App\Core\Auth\Recaller
     */
    protected $recaller;

    /**
     * The loggedin user instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create new auth instance.
     *
     * @param HasherInterface       $hash
     * @param SessionStoreInterface $session
     * @param Recaller              $recaller
     * @param CookieJar             $cookie
     *
     * @return void
     */
    public function __construct(
        HasherInterface $hash,
        SessionStoreInterface $session,
        Recaller $recaller,
        CookieJar $cookie
    )
    {
        $this->hash = $hash;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;
    }

    /**
     * Try to log in the user.
     *
     * @param  string  $credential
     * @param  string  $password
     * @param  boolean $remember
     * @return boolean
     */
    public function attempt(string $credential, string $password, bool $remember = false): bool
    {
        // Get the user and check the credetials given
        $user = $this->getByCredential($credential);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        // Update the password hash if required (i.e if the encryption cost changes)
        if ($this->needsRehash($user)) {
            $this->updateUserPasswordHash(
                $user,
                $this->hash->create($password)
            );
        }

        // The user can login, so lets keep him in session
        // also lets set the remember cookie if he clicked the 'remember me' on login
        $this->setUserSession($user);

        if ($remember) {
            $this->setRememberToken($user);
        }

        return true;
    }

    /**
     * Log the user out by clearing cookies, sessions everything
     *
     * @return void
     */
    public function logout()
    {
        $this->clearUserRememberToken($this->user);
        $this->cookie->clear('remember');
        $this->session->clear($this->key());
    }

    /**
     * Return the current loggedin user.
     *
     * @return \App\Models\User
     */
    public function user(): User
    {
        return $this->user;
    }

    /**
     * Do we have a logged in user?
     *
     * @return bool
     */
    public function check(): bool
    {
        return $this->hasUserInSession();
    }

    /**
     * Persist the logged in user.
     *
     * @return  void
     */
    public function setUserFromSession()
    {
        // Get the user by its session ID
        $user = User::find(
            $this->session->get($this->key())
        );

        if (!$user) {
            throw new \Exception('Auth user not found.');
        }

        // Set the user as Auth user
        $this->user = $user;
    }

    /**
     * Login the user using cookie.
     *
     * @return void
     */
    public function setUserFromCookie()
    {
        // Get the identifier and the token
        list($identifier, $token) = $this->recaller->splitCookieValue(
            $this->cookie->get('remember')
        );

        // Try to get the user and clear the cookie if no user found
        if (!$user = $this->getByRememberIdentifier($identifier)) {
            $this->cookie->clear('remember');
            return;
        }

        // Validate the user's token
        if (!$this->recaller->validateToken($token, $user->remember_token)) {

            // Clear identifier and token from db and also the cookie for security reasons
            $this->clearUserRememberToken($user);

            $this->cookie->clear('remember');

            throw new \Exception('The token has been cleared.');
        }

        // Finally we can sing in the user
        $this->setUserSession($user);
    }

    /**
     * Do we have a 'remember' cookie set already?
     *
     * @return boolean
     */
    public function hasRecaller(): bool
    {
        return $this->cookie->exists('remember');
    }

    /**
     * Do we have a user stored in session?
     *
     * @return boolean
     */
    protected function hasUserInSession(): bool
    {
        return $this->session->exists($this->key());
    }

    /**
     * Set a remember token if the user ticked the remember me.
     *
     * @param  User $user
     */
    protected function setRememberToken(User $user)
    {
        // Generate a unique identifier and unique token
        // that we will insert into the cookie
        list($identifier, $token) = $this->recaller->generate();

        // Set the cookie
        $this->cookie->set(
            'remember',
            $this->recaller->generateValueForCookie($identifier, $token)
        );

        // Save the identifier and the token in the db
        $this->setUserRememberToken(
            $user,
            $identifier,
            $this->recaller->getTokenHashed($token)
        );
    }

    /**
     * Set a remember token for the user.
     *
     * @param User   $user
     * @param string $identifier
     * @param string $hash
     */
    protected function setUserRememberToken(User $user, string $identifier, string $hash)
    {
        return User::find($user->id)->update([
            'remember_identifier' => $identifier,
            'remember_token' => $hash
        ]);
    }

    /**
     * Set the user session.
     *
     * @param \App\Models\User $user
     */
    protected function setUserSession(User $user)
    {
        // REMEMBER: Very careful what user data you 're going to store in session!
        // This could cause many vulnerability issues!
        $this->session->set($this->key(), $user->id);
    }

    /**
     * Update the user's password hash.
     *
     * @param  User   $user
     * @param  string $hash
     */
    protected function updateUserPasswordHash(User $user, string $hash)
    {
        return User::find($user->id)->update([
            'password' => $hash
        ]);
    }

    /**
     * Find a user by his login credential. The credential would be
     * either his email or his username
     *
     * @return \App\Models\User|null
     */
    protected function getByCredential(string $credential): ?User
    {
        return User::where('email', $credential)->first();
    }

    /**
     * Find a user by the remember identifier
     *
     * @return \App\Models\User|null
     */
    protected function getByRememberIdentifier(string $identifier): ?User
    {
        return User::where('remember_identifier', $identifier)->first();
    }

    /**
     * Delete user's remember identifier and the remember token from the database.
     *
     * @param  null|User   $user
     */
    protected function clearUserRememberToken(?User $user)
    {
        if (!$user) {
            return;
        }

        return User::find($user->id)->update([
            'remember_identifier' => null,
            'remember_token' => null,
        ]);
    }

    /**
     * Does the user have valid credentials to login?
     *
     * @param  \App\Models\User  $user
     * @param  string            $password
     * @return boolean
     */
    protected function hasValidCredentials(User $user, string $password): bool
    {
        return $this->hash->check($password, $user->password);
    }

    /**
     * Do we need to rehash the user's password?
     *
     * @param  \App\Models\User   $user
     * @return bool
     */
    protected function needsRehash(User $user): bool
    {
        return $this->hash->needsRehash($user->password);
    }

    /**
     * Return the key we keep in session for the logged in user.
     * Typically this would be the user ID.
     *
     * @return string
     */
    protected function key(): string
    {
        return 'id';
    }
}