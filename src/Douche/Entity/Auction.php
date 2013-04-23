<?php

namespace Douche\Entity;

use Douche\Value\Bid;
use Douche\Exception\BidTooLowException;

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

    public function bid(User $bidder, Bid $bid)
    {
        $highestBid = $this->getHighestBid();

        if ($highestBid && $bid->getAmount() <= $highestBid->getAmount()) {
            throw new BidTooLowException();
        }

        $this->bids[] = [$bidder, $bid];
    }

    public function getHighestBid()
    {
        list($bidder, $bid) = $this->getHighestBidTuple();

        return $bid;
    }

    public function getHighestBidder()
    {
        list($bidder, $bid) = $this->getHighestBidTuple();

        return $bidder;
    }

    private function getHighestBidTuple()
    {
        return end($this->bids);
    }
}
