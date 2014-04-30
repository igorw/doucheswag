<?php

namespace Douche\Entity;

interface AuctionRepository
{
    function findAll();
    function find($id);
    function createAuction($name, $endsAt, $currency);
}
