<?php

namespace Douche\Interactor;

use Douche\Value\Currency;
use Douche\Value\Money;

interface CurrencyConverter 
{
    /** @return Money */
    public function convert(Money $money, Currency $currency);
}
