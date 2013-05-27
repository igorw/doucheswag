<?php

namespace Douche\Repository;

use Douche\Entity\User;

interface UserRepository
{
    function find($id);
    function findOneByEmail($email);
    function add(User $user);
}
