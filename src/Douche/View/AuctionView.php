<?php

namespace Douche\View;

use Douche\Entity\Auction;

class AuctionView
{
    public $id;
    public $name;
    public $highestBid;
    public $highestBidder;
    public $isRunning;
    public $endingAt;

    public function __construct(array $attributes = array())
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }
    }

    public static function fromEntity(Auction $auction)
    {
        return new static([
            'id'            => $auction->getId(),
            'name'          => $auction->getName(),
            'highestBid'    => $auction->getHighestBid(),
            'highestBidder' => $auction->getHighestBidder()
                                ? $auction->getHighestBidder()->getId()
                                : null,
            'isRunning'     => $auction->isRunning(),
            'endingAt'      => $auction->getEndingAt(),
        ]);
    }
}
