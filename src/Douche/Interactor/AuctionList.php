<?php

namespace Douche\Interactor;

use Douche\Requestor\Request;
use Douche\Requestor\Interactor;
use Douche\Entity\Auction;
use Douche\Entity\AuctionRepository;
use Douche\View\AuctionView;

class AuctionList implements Interactor
{
    private $repo;

    public function __construct(AuctionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Request $request)
    {
        $auctions = $this->repo->findAll();
        $auctionViews = array_map([$this, 'createAuctionView'], $auctions);

        return new AuctionListResponse($auctionViews);
    }

    public function createAuctionView(Auction $auction)
    {
        return new AuctionView([
            'name' => $auction->getName(),
        ]);
    }
}
