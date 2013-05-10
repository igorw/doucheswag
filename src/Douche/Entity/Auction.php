<?php

namespace Douche\Entity;

use Douche\Value\Bid;
use Douche\Exception\BidTooLowException;
use Douche\Exception\AuctionClosedException;
use DateTime;
use Money\Currency;

class Auction
{
    private $id;
    private $name;
    private $endingAt;
    protected $bids = [];

    public function __construct($id, $name, DateTime $endingAt, Currency $currency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->endingAt = $endingAt;
        $this->currency = $currency;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function bid(User $bidder, Bid $bid, \DateTime $now = null)
    {
        if (!$this->isRunning($now)) {
            throw new AuctionClosedException();
        }

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

    public function isRunning(\DateTime $now = null)
    {
        $now = $now ?: new DateTime();
        return $this->endingAt > $now;
    }

    private function getHighestBidTuple()
    {
        return end($this->bids);
    }
}
