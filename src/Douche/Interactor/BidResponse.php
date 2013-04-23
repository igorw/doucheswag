<?php

namespace Douche\Interactor;

use Douche\Value\Bid as BidValue;

class BidResponse
{
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED_TOO_LOW = 'failed_too_low';

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
}
