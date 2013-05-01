<?php

namespace Douche\Interactor;

use Money\Currency;
use Money\Money;

interface CurrencyConverter 
{
    /** @return Money */
    public function convert(Money $money, Currency $currency);
}
