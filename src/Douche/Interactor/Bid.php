<?php

namespace Douche\Interactor;

use Douche\Entity\AuctionRepository;
use Douche\Entity\UserRepository;
use Douche\Value\Bid as BidValue;
use Douche\View\AuctionView as AuctionViewDto;
use Douche\Exception\Exception;

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
        $status = BidResponse::STATUS_SUCCESS;

        try {
            $auction->bid($user, $bid);
        } catch (Exception $e) {
            return BidResponse::fromException($bid, $e);
        }

        return new BidResponse($bid, $status);
    }
}
