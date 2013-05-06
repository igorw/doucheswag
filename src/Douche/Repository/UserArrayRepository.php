<?php

namespace Douche\Repository;

use Douche\Entity\User;
use Douche\Entity\UserRepository;

class UserArrayRepository implements UserRepository
{
    private $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function add(User $user)
    {
        if (null !== $this->find($user->getId())) {
            throw new \InvalidArgumentException('User already exists');
        }

        $this->users[] = $user;
    }

    public function find($id)
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }

        return null;
    }
}
