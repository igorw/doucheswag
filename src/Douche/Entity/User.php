<?php

namespace Douche\Entity;

class User
{
    private $id;
    private $name;
    private $email;
    private $passwordHash;

    public function __construct($id, $name, $email, $passwordHash)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }
}
