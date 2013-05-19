<?php

namespace Douche\Service;

use Money\Money;
use Money\Currency;
use Money\CurrencyPair;

class PairCurrencyConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException RuntimeException
     */
    public function convertWithoutCurrenciesShouldFail()
    {
        $converter = new PairCurrencyConverter([]);

        $eur = Money::EUR(100);
        $converter->convert($eur, new Currency('USD'));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function convertWithWrongCurrencyShouldFail()
    {
        $converter = new PairCurrencyConverter([
            CurrencyPair::createFromIso('EUR/CHF 1.2500'),
        ]);

        $eur = Money::EUR(100);
        $converter->convert($eur, new Currency('USD'));
    }

    /** @test */
    public function convertShouldUseSuppliedCurrencyPair()
    {
        $converter = new PairCurrencyConverter([
            CurrencyPair::createFromIso('EUR/USD 1.2500'),
        ]);

        $eur = Money::EUR(100);
        $usd = $converter->convert($eur, new Currency('USD'));

        $this->assertEquals(Money::USD(125), $usd);
    }

    /** @test */
    public function convertShouldConvertToSameCurrency()
    {
        $converter = new PairCurrencyConverter([]);

        $eur = Money::EUR(100);
        $converted = $converter->convert($eur, new Currency('EUR'));

        $this->assertEquals(Money::EUR(100), $converted);
    }
}
