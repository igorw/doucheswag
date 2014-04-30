<?php

namespace Douche\Repository;

interface AuctionRepository
{
    function findAll();
    function find($id);
}
