<?php

namespace Douche\Interactor;

use Douche\Value\Bid as BidValue;
use Douche\Exception\Exception;

class BidResponse
{
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED_TOO_LOW = 'failed_too_low';
    const STATUS_FAILED_AUCTION_CLOSED = 'failed_auction_closed';
    const STATUS_FAILED = 'failed';

    private $bid;

    public function __construct(BidValue $bid, $status)
    {
        $this->bid = $bid;
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isSuccess()
    {
        return $this->status == static::STATUS_SUCCESS;
    }

    public static function fromException(BidValue $bid, Exception $e)
    {
        $status = static::STATUS_FAILED;

        switch(get_class($e)) {
            case "Douche\Exception\BidTooLowException":
                $status = static::STATUS_FAILED_TOO_LOW;
                break;

            case "Douche\Exception\AuctionClosedException":
                $status = static::STATUS_FAILED_AUCTION_CLOSED;
                break;
        }

        return new static($bid, $status);
    }
}
