<?php

namespace Douche\Exception;

class UserNotFoundException extends Exception
{
    public $email;

    public function __construct($email)
    {
        parent::__construct();

        $this->email = $email;
    }
}
