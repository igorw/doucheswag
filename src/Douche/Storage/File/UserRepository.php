<?php

namespace Douche\Storage\File;

use Douche\Entity\User;
use Douche\Entity\UserRepository as UserRepositoryInterface;
use Douche\Exception\UserNotFoundException;

class UserRepository implements UserRepositoryInterface
{
    private $file;
    private $users = [];

    public function __construct($file)
    {
        $this->file = $file;
        $this->users = $this->loadUsers();
    }

    public function find($id)
    {
        return $this->findOneByField('id', $id);
    }

    public function findOneByEmail($email)
    {
        return $this->findOneByField('email', $email);
    }

    public function add(User $user)
    {
        $this->users[] = $user;
    }

    public function save()
    {
        $rawUsers = $this->serializeUsers();
        $json = json_encode($rawUsers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->file, $json."\n");
    }

    private function findOneByField($field, $value)
    {
        foreach ($this->users as $user) {
            $getter = 'get'.$field;
            if ($value === $user->$getter()) {
                return $user;
            }
        }

        return null;
    }

    private function loadUsers()
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $rawUsers = json_decode(file_get_contents($this->file), true) ?: [];

        return array_map(
            function ($rawUser) {
                return new User(
                    $rawUser['id'],
                    $rawUser['name'],
                    $rawUser['email'],
                    $rawUser['passwordHash']
                );
            },
            $rawUsers
        );
    }

    private function serializeUsers()
    {
        return array_map(
            function ($user) {
                return [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'passwordHash' => $user->getPasswordHash(),
                ];
            },
            $this->users
        );
    }
}
