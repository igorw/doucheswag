<?php

namespace Douche\Interactor;

use Douche\Value\Bid as BidValue;
use Douche\Exception\Exception;

class BidResponse
{
    public $bid;

    public function __construct(BidValue $bid)
    {
        $this->bid = $bid;
    }
}
