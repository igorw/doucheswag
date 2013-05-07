<?php

namespace Douche\Interactor;

interface PasswordEncoder
{
    function encodePassword($password);
}
