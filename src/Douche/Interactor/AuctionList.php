<?php

namespace Douche\Interactor;

use Douche\Entity\Auction;
use Douche\Entity\AuctionRepository;
use Douche\View\AuctionView;

class AuctionList
{
    private $repo;

    public function __construct(AuctionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke()
    {
        $auctions = $this->repo->findAll();
        $auctionViews = array_map([$this, 'createAuctionView'], $auctions);

        return new AuctionListResponse($auctionViews);
    }

    public function createAuctionView(Auction $auction)
    {
        return new AuctionView([
            'id'   => $auction->getId(),
            'name' => $auction->getName(),
        ]);
    }
}
