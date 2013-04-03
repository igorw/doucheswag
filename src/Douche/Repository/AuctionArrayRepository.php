<?php

namespace Douche\Repository;

use Douche\Entity\AuctionRepository;

class AuctionArrayRepository implements AuctionRepository
{
    private $auctions;

    public function __construct(array $auctions)
    {
        $this->auctions = $auctions;
    }

    public function findAll()
    {
        return $this->auctions;
    }
}
