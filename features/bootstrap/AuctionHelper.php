<?php

use Douche\Entity\Auction;
use Douche\Entity\User;
use Douche\Interactor\AuctionList;
use Douche\Interactor\AuctionView as AuctionViewInteractor;
use Douche\Interactor\AuctionViewRequest;
use Douche\Interactor\Bid as BidInteractor;
use Douche\Interactor\BidRequest;
use Douche\Repository\AuctionArrayRepository;
use Douche\Repository\UserArrayRepository;
use Douche\Value\Bid as BidValue;
use Douche\Value\Currency;
use Douche\View\AuctionView;
use Douche\Exception\Exception as DoucheException;

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class AuctionHelper
{
    protected $auctionRepo;
    protected $userRepo;
    protected $auctions = array();
    protected $users = array();
    protected $auction;
    protected $response;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function createAuction($name, $endingAt = null)
    {
        $endingAt = $endingAt ?: new \DateTime("+10 days");
        $this->auctions[] = $auction = new Auction(count($this->auctions) + 1, $name, $endingAt, new Currency("BTC", "Bitcoin"));
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

    public function placeBid($amount, User $user = null)
    {
        if ($user == null) {
            $user = new User(uniqid());
            $this->users[] = $user;
        }

        $interactor = new BidInteractor($this->getAuctionRepository(), $this->getUserRepository());
        $request = new BidRequest($this->auction->getId(), $user->getId(), $amount);

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

    public function assertBidRejected()
    {
        assertInstanceOf("Douche\Exception\BidRejectedException", $this->response);
    }

    protected function getAuctionRepository()
    {
        $this->auctionRepo = $this->auctionRepo ?: new AuctionArrayRepository($this->auctions);

        return $this->auctionRepo;
    }

    protected function getUserRepository()
    {
        $this->userRepo = $this->userRepo ?: new UserArrayRepository($this->users);

        return $this->userRepo;
    }
}
