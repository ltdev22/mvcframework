<?php

namespace App\Auth;

use Doctrine\ORM\EntityManager;
use App\Auth\Recaller;
use App\Models\User;
use App\Auth\Hashing\HasherInterface;
use App\Session\SessionStoreInterface;
use App\Cookie\CookieJar;

class Auth
{
    /**
     * The Doctrine instance
     * @var \Doctrine\ORM\EntityManager
     */
    protected $db;

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
     * Create new auth instance.
     *
     * @param EntityManager         $db
     * @param HasherInterface       $hash
     * @param SessionStoreInterface $session
     * @param Recaller              $recaller
     * @param CookieJar             $cookie
     * @return void
     */
    public function __construct(
        EntityManager $db,
        HasherInterface $hash,
        SessionStoreInterface $session,
        Recaller $recaller,
        CookieJar $cookie
    )
    {
        $this->db = $db;
        $this->hash = $hash;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;
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
        $user = $this->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        if ($this->needsRehash($user)) {
            $this->rehashPassword($user, $password);
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
     * Persist the logged in user.
     *
     * @return  void
     */
    public function setUserFromSession()
    {
        // Get the user by its session ID
        $user = $this->getById($this->session->get($this->key()));

        if (!$user) {
            throw new \Exception('Auth user not found.');
        }

        // Set the user as Auth user
        $this->user = $user;
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
     * Rehash user's password.
     *
     * @param  \App\Models\User   $user
     * @param  string             $password
     */
    protected function rehashPassword(User $user, string $password): bool
    {
        $this->updateUser($user, [
            'password' => $this->hash->create($password),
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
        $this->updateUser($user, [
            'remember_identifier' => $identifier,
            'remember_token' => $this->recaller->getTokenHashed($token),
        ]);
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

    /**
     * Return the user record by the ID.
     *
     * @param  int   $id
     * @return \App\Models\User|null
     */
    protected function getById(int $id): ?User
    {
        return $this->db->getRepository(User::class)->find($id);
    }

    /**
     * Return the user record finded be the username
     * (in this case is by email actually)
     *
     * @param  string   $username
     * @return \App\Models\User|null
     */
    protected function getByUsername(string $username): ?User
    {
        return $this->db->getRepository(User::class)->findOneBy([
            'email' => $username
        ]);
    }

    /**
     * Save data to the user model.
     *
     * @param  User   $user
     * @param  array  $data
     * @return void
     */
    protected function updateUser(User $user, array $data)
    {
        $this->db->getRepository(User::class)
                    ->find($user->id)
                    ->update($data);

        $this->db->flush();
    }
}