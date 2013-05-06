<?php

namespace Douche\Interactor;

class RegisterUserRequest
{
    public $id;
    public $name;
    public $email;
    public $password;

    public function __construct(array $properties)
    {
        foreach ($properties as $name => $value) {
            $this->$name = $value;
        }
    }
}
