<?php

namespace Douche\Entity;

interface UserRepository
{
    function find($id);
    function findOneByEmail($email);
    function add(User $user);
}
