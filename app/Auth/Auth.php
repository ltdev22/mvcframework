<?php

namespace App\Auth;

use App\Auth\Recaller;
use App\Models\User;
use App\Auth\Hashing\HasherInterface;
use App\Auth\Providers\UserProviderInterface;
use App\Session\SessionStoreInterface;
use App\Cookie\CookieJar;

class Auth
{

    /**
     * The hash instance.
     *
     * @var \App\Auth\Hashing\HasherInterface
     */
    protected $hash;

    /**
     * The session instance.
     *
     * @var \App\Session\SessionStoreInterface
     */
    protected $session;

    /**
     * The cookie instance.
     *
     * @var \App\Cookie\CookieJar
     */
    protected $cookie;

    /**
     * The recaller instance.
     *
     * @var \App\Auth\Recaller
     */
    protected $recaller;

    /**
     * The loggedin user instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * The user provider instance.
     *
     * @var \App\Auth\Providers\UserProviderInterface
     */
    protected $provider;

    /**
     * Create new auth instance.
     *
     * @param HasherInterface       $hash
     * @param SessionStoreInterface $session
     * @param Recaller              $recaller
     * @param CookieJar             $cookie
     * @param UserProviderInterface $user
     * @return void
     */
    public function __construct(
        HasherInterface $hash,
        SessionStoreInterface $session,
        Recaller $recaller,
        CookieJar $cookie,
        UserProviderInterface $provider
    )
    {
        $this->hash = $hash;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;
        $this->provider = $provider;
    }

    /**
     * Try to log in the user.
     *
     * @param  string  $username
     * @param  string  $password
     * @param  boolean $remember
     * @return boolean
     */
    public function attempt(string $username, string $password, bool $remember = false): bool
    {
        // Get the user and check the credetials given
        $user = $this->provider->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        if ($this->needsRehash($user)) {
            $this->provider->updateUserPasswordHash(
                $user,
                $this->hash->create($password)
            );
        }

        // The user can login, so lets keep him in session
        $this->setUserSession($user);

        if ($remember) {
            $this->setRememberToken($user);
        }

        return true;
    }

    /**
     * Log the user out.
     *
     * @return void
     */
    public function logout()
    {
        $this->provider->clearUserRememberToken($this->user);
        $this->cookie->clear('remember');
        $this->session->clear($this->key());
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
     * Return the current loggedin user.
     *
     * @return \App\Models\User
     */
    public function user(): User
    {
        return $this->user;
    }

    /**
     * Do we have a user stored in session?
     *
     * @return boolean
     */
    public function hasUserInSession(): bool
    {
        return $this->session->exists($this->key());
    }

    /**
     * Set a remember token if the user ticked the remember me.
     *
     * @param  User $user
     */
    protected function setRememberToken($user)
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
        $this->provider->setUserRememberToken(
            $user,
            $identifier,
            $this->recaller->getTokenHashed($token)
        );
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
     * Persist the logged in user.
     *
     * @return  void
     */
    public function setUserFromSession()
    {
        // Get the user by its session ID
        $user = $this->provider->getById($this->session->get($this->key()));

        if (!$user) {
            throw new \Exception('Auth user not found.');
        }

        // Set the user as Auth user
        $this->user = $user;
    }


    public function setUserFromCookie()
    {
        // Get the identifier and the token
        list($identifier, $token) = $this->recaller->splitCookieValue(
            $this->cookie->get('remember')
        );

        // Try to get the user and clear the cookie if no user found
        if (!$user = $this->provider->getByRememberIdentifier($identifier)) {
            $this->cookie->clear('remember');
            return;
        }

        // Validate the user's token
        if (!$this->recaller->validateToken($token, $user->remember_token)) {

            // Clear identifier and token from db and also the cookie for security reasons
            $this->provider->clearUserRememberToken($user);

            $this->cookie->clear('remember');

            throw new \Exception("Error Processing Request", 1);
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