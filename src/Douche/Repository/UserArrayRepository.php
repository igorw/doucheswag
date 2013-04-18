<?php

namespace Douche\Repository;

use Douche\Entity\UserRepository;

class UserArrayRepository implements UserRepository
{
    private $users;

    public function __construct(array $users)
    {
        $this->users = $users;
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
