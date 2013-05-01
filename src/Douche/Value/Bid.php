<?php

namespace Douche\Value;

use Money\Money;

class Bid
{
    private $amount;
    private $originalAmount;

    public function __construct(Money $amount, Money $originalAmount)
    {
        $this->amount = $amount;
        $this->originalAmount = $originalAmount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getOriginalAmount()
    {
        return $this->originalAmount;
    }
}
