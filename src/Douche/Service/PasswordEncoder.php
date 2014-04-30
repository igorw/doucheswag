<?php

namespace Douche\Service;

interface PasswordEncoder
{
    function encodePassword($password);
    function isPasswordValid($encoded, $raw);
}
