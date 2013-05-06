<?php

namespace Douche\Entity;

class User
{
    private $id;
    private $name;
    private $email;
    private $passwordHash;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
