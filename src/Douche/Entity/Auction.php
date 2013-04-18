<?php

namespace Douche\Entity;

use Douche\Value\Bid;

class Auction
{
    private $id;
    private $name;
    private $bids = [];

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function bid(User $user, Bid $bid)
    {
        $this->bids[] = [$user, $bid];
    }
}
