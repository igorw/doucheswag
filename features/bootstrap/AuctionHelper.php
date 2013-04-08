<?php

use Douche\Entity\Auction;
use Douche\Interactor\AuctionList;
use Douche\Interactor\AuctionView as AuctionViewInteractor;
use Douche\Interactor\AuctionViewRequest;
use Douche\Repository\AuctionArrayRepository;
use Douche\View\AuctionView;

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class AuctionHelper
{
    protected $repo;
    protected $auctions = array();
    protected $auction;
    protected $response;

    public function createAuction($name)
    {
        $this->auctions[] = $auction = new Auction(count($this->auctions) + 1, $name);
        $this->auction = $auction;
    }

    public function truncateAuctions()
    {
        $this->auctions = array();
        $this->repo = null;
    }

    public function listAuctions()
    {
        $interactor = new AuctionList($this->getRepository());
        $this->response = $interactor();
    }

    public function viewAuction()
    {
        $interactor = new AuctionViewInteractor($this->getRepository());
        $request = new AuctionViewRequest($this->auction->getId());
        $this->response = $interactor($request);
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

    protected function getRepository()
    {
        if ($this->repo) {
            return $this->repo;
        }

        return $this->repo = new AuctionArrayRepository($this->auctions);
    }
}
