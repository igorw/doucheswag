<?php

namespace Douche\Interactor;

class UserLoginRequest 
{
    public $email;
    public $password;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }    
}
