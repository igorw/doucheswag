<?php

namespace Douche\Exception;

class IncorrectPasswordException extends Exception
{
    public $email;

    public function __construct($email)
    {
        parent::__construct();

        $this->email = $email;
    }
}
