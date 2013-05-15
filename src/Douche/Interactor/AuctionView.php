<?php

namespace Douche\Interactor;

use Douche\Entity\AuctionRepository;
use Douche\View\AuctionView as AuctionViewDto;

class AuctionView
{
    private $repo;

    public function __construct(AuctionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(AuctionViewRequest $request)
    {
        $auction = $this->repo->find($request->id);

        $view = new AuctionViewDto([
            'id'            => $auction->getId(),
            'name'          => $auction->getName(),
            'highestBid'    => $auction->getHighestBid(),
            'highestBidder' => $auction->getHighestBidder()
                                ? $auction->getHighestBidder()->getId()
                                : null,
        ]);

        return new AuctionViewResponse($view);
    }
}
