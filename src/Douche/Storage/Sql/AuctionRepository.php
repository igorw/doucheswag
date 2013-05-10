<?php

namespace Douche\Storage\Sql;

use Doctrine\Dbal\Connection;
use Douche\Entity\UserRepository;
use Douche\Entity\AuctionRepository as AuctionRepositoryInterface;
use Douche\Value\Bid;
use Money\Money;
use Money\Currency;

class AuctionRepository 
{
    const SELECT_SQL = "
            SELECT      
                a.id as id,
                a.name as name,
                a.ending_at as ending_at,
                a.currency as currency,
                ab.id as bid_id,
                ab.user_id as bid_user_id,
                ab.amount as bid_amount,
                ab.currency as bid_currency,
                ab.original_amount as bid_original_amount,
                ab.original_currency as bid_original_currency
            FROM auctions a
            LEFT JOIN auction_bids ab ON a.id = ab.auction_id
    ";

    protected $conn;
    protected $userRepo;

    public function __construct(Connection $conn, UserRepository $userRepo)
    {
        $this->conn = $conn;
        $this->userRepo = $userRepo;
    }

    public function findAll()
    {
        $rows = $this->conn->fetchAll(static::SELECT_SQL);
        if (empty($rows)) {
            return [];
        }

        return $this->rowsToAuctions($rows);
    }

    public function find($id) 
    {
        $rows = $this->conn->fetchAll(static::SELECT_SQL . " WHERE a.id = ?", [$id]);

        if (empty($rows)) {
            return null;
        }

        return reset($this->rowsToAuctions($rows));
    }

    protected function rowsToAuctions($rows)
    {
        /* this could be pulled out into a seperate class and tested in isolation */
        $auctions = array();

        foreach($rows as $row) {

            $id = $row['id'];

            if (!isset($auctions[$id])) {
                $auctions[$id] = new Entity\Auction(
                    $id,
                    $row['name'], 
                    new \DateTime($row['ending_at']),
                    new Currency($row['currency'])
                );
            }

            if (!empty($row['bid_id'])) {
                $amount = new Money((int) $row['bid_amount'], new Currency($row['bid_currency']));
                $originalAmount = new Money((int) $row['bid_original_amount'], new Currency($row['bid_original_currency']));

                $bid = new Bid($amount, $originalAmount);

                /* could look to proxy this */
                $bidder = $this->userRepo->find($row['bid_user_id']);

                $auctions[$id]->addBid($bidder, $bid);
            }
        }

        return $auctions;
    }
}
