<?php

use Douche\Entity\Auction;
use Douche\Entity\User;
use Douche\Entity\UserRepository;
use Douche\Interactor\AuctionList;
use Douche\Interactor\AuctionView as AuctionViewInteractor;
use Douche\Interactor\AuctionViewRequest;
use Douche\Interactor\Bid as BidInteractor;
use Douche\Interactor\BidRequest;
use Douche\Repository\AuctionArrayRepository;
use Douche\Value\Bid as BidValue;
use Douche\View\AuctionView;
use Douche\Exception\Exception as DoucheException;
use Douche\Service\DumbCurrencyConverter;
use Money\Money;
use Money\Currency;

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class AuctionHelper
{
    protected $auctionRepo;
    protected $userHelper;
    protected $auctions = array();
    protected $auction;
    protected $response;

    public function __construct(UserHelper $userHelper)
    {
        $this->userHelper = $userHelper;
    }

    public function createAuction($name, $endingAt = null)
    {
        $endingAt = $endingAt ?: new \DateTime("+10 days");
        $this->auctions[] = $auction = new Auction(count($this->auctions) + 1, $name, $endingAt, new Currency("USD"));
        $this->auction = $auction;
    }

    public function truncateAuctions()
    {
        $this->auctions = array();
        $this->auctionRepo = null;
    }

    public function listAuctions()
    {
        $interactor = new AuctionList($this->getAuctionRepository());
        $this->response = $interactor();
    }

    public function viewAuction()
    {
        $interactor = new AuctionViewInteractor($this->getAuctionRepository());
        $request = new AuctionViewRequest($this->auction->getId());
        $this->response = $interactor($request);
    }

    public function placeBidWithAlternateCurrency($amount, $userId = null) {
        return $this->placeBid($amount, $userId, new Currency("GBP"));
    }

    public function placeBid($amount, Currency $currency = null)
    {
        $userId = $this->getUserHelper()->getCurrentUserId();

        if ($userId == null) {
            $userId = $this->getUserHelper()->createUser();
        }

        $interactor = new BidInteractor(
            $this->getAuctionRepository(),
            $this->getUserHelper()->getUserRepository(),
            new DumbCurrencyConverter()
        );

        $amount = new Money(intval($amount * 100), $currency ?: $this->auction->getCurrency());
        $request = new BidRequest($this->auction->getId(), $userId, $amount);

        try {
            $this->response = $interactor($request);
        } catch (DoucheException $e) {
            $this->response = $e;
        }
    }

    public function assertAuctionPresent()
    {
        assertInstanceOf("Douche\View\AuctionView", $this->response->auction);
    }

    public function assertNoRunningAuctions()
    {
        assertEquals(0, count($this->response->auctions));
    }

    public function assertSomeRunningAuctions()
    {
        assertGreaterThan(0, count($this->response->auctions));
    }

    public function assertBidAccepted()
    {
        assertInstanceOf("Douche\Interactor\BidResponse", $this->response);
    }

    public function assertBidAcceptedWithCurrencyConversion()
    {
        assertInstanceOf("Douche\Interactor\BidResponse", $this->response);
        assertNotSame(
            $this->response->getBid()->getAmount(),
            $this->response->getBid()->getOriginalAmount()
        );
    }

    public function assertBidRejected()
    {
        assertInstanceOf("Douche\Exception\BidRejectedException", $this->response);
    }

    protected function getUserHelper()
    {
        return $this->userHelper;
    }

    protected function getAuctionRepository()
    {
        $this->auctionRepo = $this->auctionRepo ?: new AuctionArrayRepository($this->auctions);

        return $this->auctionRepo;
    }
}
