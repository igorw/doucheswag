<?php

use Douche\Repository\AuctionArrayRepository;
use Douche\Interactor\AuctionList;

$container = new Pimple();

$container['auctions'] = array();

$container['auctions.repo'] = $container->share(function($container) {
    return new AuctionArrayRepository($container['auctions']);
});

$container['interactor.auction_list'] = $container->share(function($container) {
    return new AuctionList($container['auctions.repo']);
});


return $container;
