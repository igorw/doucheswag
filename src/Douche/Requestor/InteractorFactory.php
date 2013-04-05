<?php

namespace Douche\Requestor;

class InteractorFactory 
{
    private $container;

    public function __construct(\ArrayAccess $container = null)
    {
        $this->container = $container ?: new \ArrayObject();
    }

    public function make($interactorName) 
    {
        return $this->container["interactor.".$interactorName];
    }    

    public function register($interactorName, $object)
    {
        $this->container["interactor.".$interactorName] = $object;
    }
}
