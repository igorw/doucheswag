<?php

namespace Douche\Entity;

interface UserRepository
{
    function find($id);
    function add(User $user);
}
