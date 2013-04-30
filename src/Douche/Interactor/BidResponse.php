<?php

namespace Douche\Interactor;

use Douche\Value\Bid as BidValue;
use Douche\Exception\Exception;

class BidResponse
{
    private $bid;

    public function __construct(BidValue $bid)
    {
        $this->bid = $bid;
    }

    public function getBid()
    {
        return $this->bid;
    }
}
