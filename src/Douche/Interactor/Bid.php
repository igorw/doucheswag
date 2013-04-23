<?php

namespace Douche\Interactor;

use Douche\Entity\AuctionRepository;
use Douche\Entity\UserRepository;
use Douche\Value\Bid as BidValue;
use Douche\View\AuctionView as AuctionViewDto;
use Douche\Exception\AuctionClosedException;

class Bid
{
    private $auctionRepo;
    private $userRepo;

    public function __construct(AuctionRepository $auctionRepo, UserRepository $userRepo)
    {
        $this->auctionRepo = $auctionRepo;
        $this->userRepo = $userRepo;
    }

    public function __invoke(BidRequest $request)
    {
        $auction = $this->auctionRepo->find($request->auctionId);
        $user = $this->userRepo->find($request->userId);

        $bid = new BidValue($request->amount);
        $auction->bid($user, $bid);
        return new BidResponse($bid);
    }
}
