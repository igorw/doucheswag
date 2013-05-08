<?php

namespace Douche\Interactor;

use Douche\View\UserView;

class UserLoginResponse 
{
    public $user;

    public function __construct(UserView $user)
    {
        $this->user = $user;
    }    
}
