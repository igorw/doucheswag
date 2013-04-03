<?php

namespace Douche\Interactor;

class AuctionListResponse
{
    public $auctions;

    public function __construct(array $auctions)
    {
        $this->auctions = $auctions;
    }
}
