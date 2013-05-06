<?php

namespace Douche\Service;

use Douche\Interactor\PasswordEncoder;

class UppercasePasswordEncoder implements PasswordEncoder
{
    public function encodePassword($password)
    {
        return strtoupper($password);
    }
}
