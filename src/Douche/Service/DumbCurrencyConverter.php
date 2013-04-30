<?php

namespace Douche\Service;

use Douche\Interactor\CurrencyConverter;
use Douche\Value\Money;
use Douche\Value\Currency;

class DumbCurrencyConverter implements CurrencyConverter
{
    public function convert(Money $money, Currency $currency) 
    {
        return new Money($money->getAmount(), $currency);
    }
}
