<?php

namespace Douche\View;

class AuctionView
{
    public $name;

    public function __construct(array $attributes = array())
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }
    }
}
