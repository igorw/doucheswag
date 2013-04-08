<?php

namespace Douche\Interactor;

class AuctionViewRequest 
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    } 
}
