<?php

namespace Douche\Storage\Sql\Entity;

use Douche\Value\Bid;
use Douche\Entity\User;
use Douche\Entity\Auction as BaseAuction;

class Auction extends BaseAuction
{
    /**
     * This could really be promoted to the public api, I'm sure we can find a 
     * use for it
     */
    public function addBid(User $bidder, Bid $bid)
    {
        $this->bids[] = [$bidder, $bid];
    }
}
