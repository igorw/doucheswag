<?php

namespace Douche\View;

class AuctionView
{
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
