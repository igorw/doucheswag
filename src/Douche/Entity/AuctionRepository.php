<?php

namespace Douche\Entity;

interface AuctionRepository
{
    function findAll();
    function find($id);
}
