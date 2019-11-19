<?php

namespace App\Auth\Providers;

use App\Auth\Providers\UserProviderInterface;
use App\Models\User;
use Doctrine\ORM\EntityManager;

class DatabaseProvider implements UserProviderInterface
{
    /**
     * The db instance.
     *
     * @var \use Doctrine\ORM\EntityManager;
     */
    protected $db;

    public function __construct(EntityManager $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     *
     * @return \App\Models\User|null
     */
    public function getById(int $id): ?User
    {
        return $this->db->getRepository(User::class)->find($id);
    }

    /**
     * {@inheritdoc}
     *
     * @return \App\Models\User|null
     */
    public function getByUsername(string $username): ?User
    {
        return $this->db->getRepository(User::class)->findOneBy([
            'email' => $username
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @return \App\Models\User|null
     */
    public function getByRememberIdentifier(string $identifier): ?User
    {
        return $this->db->getRepository(User::class)->findOneBy([
            'remember_identifier' => $identifier,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setUserRememberToken(User $user, string $identifier, string $hash)
    {
        $this->db->getRepository(User::class)->find($user->id)->update([
            'remember_identifier' => $identifier,
            'remember_token' => $hash
        ]);

        $this->db->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function updateUserPasswordHash(User $user, string $hash)
    {
        $this->db->getRepository(User::class)->find($user->id)->update([
            'password' => $hash
        ]);

        $this->db->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function clearUserRememberToken(User $user)
    {
        $this->db->getRepository(User::class)->find($user->id)->update([
            'remember_identifier' => null,
            'remember_token' => null,
        ]);

        $this->db->flush();
    }
}