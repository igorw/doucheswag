<?php

namespace Douche\Entity;

use Money\Money;
use Money\Currency;
use Douche\Value\Bid;

class AuctionTest extends \PHPUnit_Framework_TestCase
{
    private $now;

    public function setUp()
    {
        $this->now = new \DateTime('2012-03-02');
    }

    /** @test */
    public function bidShouldAddBidToAuction()
    {
        $auction = new Auction(1, 'YOLO Glasses',
                        new \DateTime('2012-03-04'),
                        new Currency('GBP'));

        $bidder = new User(42, 'John Doe', 'john.doe@example.com', 'foo');
        $bid = new Bid(Money::GBP(200), Money::GBP(200));
        $auction->bid($bidder, $bid, $this->now);

        $this->assertEquals($bid, $auction->getHighestBid());
        $this->assertEquals($bidder, $auction->getHighestBidder());

        return $auction;
    }

    /**
     * @test
     * @depends bidShouldAddBidToAuction
     */
    public function higherBidShouldOverrideOldOne(Auction $auction)
    {
        $bidder = new User(43, 'Jane Doe', 'jane.doe@example.com', 'bar');
        $bid = new Bid(Money::GBP(205), Money::GBP(205));
        $auction->bid($bidder, $bid, $this->now);

        $this->assertEquals($bid, $auction->getHighestBid());
        $this->assertEquals($bidder, $auction->getHighestBidder());
    }
}
