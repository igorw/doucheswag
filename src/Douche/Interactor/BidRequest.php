<?php

namespace Douche\Interactor;

use Douche\Value\Money;

class BidRequest
{
    public $auctionId;
    public $userId;
    public $amount;

    public function __construct($auctionId, $userId, Money $amount)
    {
        $this->auctionId = $auctionId;
        $this->userId = $userId;
        $this->amount = $amount;
    }
}
