<?php

namespace Douche\Interactor;

class RegisterUserResponse
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}
