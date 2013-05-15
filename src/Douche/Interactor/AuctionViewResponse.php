<?php

namespace Douche\Interactor;

use Douche\View\AuctionView as AuctionViewDto;

class AuctionViewResponse
{
    public $auction;

    public function __construct(AuctionViewDto $auction)
    {
        $this->auction = $auction;
    }
}
