<?php

namespace Douche\Service;

use Money\Money;
use Money\Currency;

class DumbCurrencyConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function convertShouldLeaveAmountUntouched()
    {
        $converter = new DumbCurrencyConverter();

        $chf = Money::CHF(100);
        $gbp = $converter->convert($chf, new Currency('GBP'));

        $this->assertEquals(Money::GBP(100), $gbp);
    }
}
