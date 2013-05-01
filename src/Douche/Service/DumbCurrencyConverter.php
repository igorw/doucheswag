<?php

namespace Douche\Service;

use Douche\Interactor\CurrencyConverter;
use Money\Money;
use Money\Currency;

class DumbCurrencyConverter implements CurrencyConverter
{
    public function convert(Money $money, Currency $currency) 
    {
        return new Money($money->getAmount(), $currency);
    }
}
