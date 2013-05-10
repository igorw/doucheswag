<?php

namespace Douche\Storage\Sql\Entity;

use Douche\Value\Bid;
use Douche\Entity\User;
use Douche\Entity\Auction as BaseAuction;

class Auction extends BaseAuction
{
    public function addBid(User $bidder, Bid $bid)
    {
        $this->bids[] = [$bidder, $bid];
    }
}
