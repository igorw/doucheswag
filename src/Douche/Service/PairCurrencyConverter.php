<?php

namespace Douche\Service;

use Douche\Interactor\CurrencyConverter;
use Money\Money;
use Money\Currency;

class PairCurrencyConverter implements CurrencyConverter
{
    private $pairs;

    public function __construct(array $pairs)
    {
        $this->pairs = $pairs;
    }

    public function convert(Money $money, Currency $currency)
    {
        $pair = $this->findPair($money->getCurrency(), $currency);

        return $pair->convert($money);
    }

    private function findPair(Currency $counterCurrency, Currency $baseCurrency)
    {
        foreach ($this->pairs as $pair) {
            if ($counterCurrency == $pair->getCounterCurrency()
                && $baseCurrency == $pair->getBaseCurrency()) {

                return $pair;
            }
        }

        throw new \RuntimeException(
            sprintf('No pair found for currencies %s and %s.',
                $counterCurrency, $baseCurrency));
    }
}
