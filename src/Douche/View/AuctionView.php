<?php

namespace Douche\View;

class AuctionView
{
    public $id;
    public $name;
    public $highestBid;
    public $highestBidder;

    public function __construct(array $attributes = array())
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }
    }
}
