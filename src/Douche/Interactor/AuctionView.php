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
            'name' => $auction->getName(),
        ]);

        return new AuctionViewResponse($view);
    }
}
