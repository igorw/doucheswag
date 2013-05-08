<?php

namespace Douche\Service;

use Douche\Interactor\PasswordEncoder;

class UppercasePasswordEncoder implements PasswordEncoder
{
    public function encodePassword($password)
    {
        return strtoupper($password);
    }

    public function isPasswordValid($encoded, $raw)
    {
        return $encoded === $this->encodePassword($raw);
    }
}
