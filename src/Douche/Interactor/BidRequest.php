<?php

namespace Douche\Interactor;

class BidRequest
{
    public $auctionId;
    public $userId;
    public $amount;

    public function __construct($auctionId, $userId, $amount)
    {
        $this->auctionId = $auctionId;
        $this->userId = $userId;
        $this->amount = $amount;
    }
}
