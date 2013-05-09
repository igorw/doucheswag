<?php

namespace Douche\View;

use Douche\Entity\User;

class UserView
{
    public $id;
    public $name;
    public $email;

    public function __construct(array $attributes = array())
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }
    }

    public static function fromUser(User $user)
    {
        return new static([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
        ]);
    }
}
