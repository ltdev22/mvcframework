<?php

namespace App\Auth;

use Doctrine\ORM\EntityManager;
use App\Models\User;
use App\Auth\Hashing\HasherInterface;
use App\Session\SessionStoreInterface;

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
     * @var \App\Auth\Hashing\HasherInterface
     */
    protected $session;

    /***/
    public function __construct(
        EntityManager $db,
        HasherInterface $hash,
        SessionStoreInterface $session
    )
    {
        $this->db = $db;
        $this->hash = $hash;
        $this->session = $session;
    }

    /**
     * Try to log in the user.
     *
     * @param  string $username
     * @param  string $password
     * @return bool
     */
    public function attempt($username, $password): bool
    {
        // Get the user and check the credetials given
        $user = $this->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        // The user can login, so lets keep him in session
        $this->setUserSession($user);

        return true;
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
     * Set the user session.
     *
     * @param \App\Models\User $user
     */
    protected function setUserSession(User $user)
    {
        // REMEMBER: Very careful what user data you 're going to store in session!
        // This could cause many vulnerability issues!
        $this->session->set('id', $user->id);
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
}