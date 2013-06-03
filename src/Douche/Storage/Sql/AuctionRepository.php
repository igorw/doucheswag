<?php

namespace Douche\Storage\Sql;

use Doctrine\Dbal\Connection;
use Douche\Entity\UserRepository;
use Douche\Entity\AuctionRepository as AuctionRepositoryInterface;
use Douche\Value\Bid;
use Money\Money;
use Money\Currency;

class AuctionRepository implements AuctionRepositoryInterface
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
    protected $identityMap = [];
    protected $originalData = [];

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
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $rows = $this->conn->fetchAll(static::SELECT_SQL . " WHERE a.id = ?", [$id]);

        if (empty($rows)) {
            return null;
        }

        $_ = $this->rowsToAuctions($rows);
        return reset($_);
    }

    public function save()
    {
        foreach ($this->identityMap as $id => $auction) {
            if (count($this->originalData[$id]['bids']) === count($auction->getBids())) {
                continue;
            }

            $this->conn->transactional(function() use ($auction) {

                $this->conn->delete('auction_bids', ['auction_id' => $auction->getId()]);

                foreach ($auction->getBids() as $tuple) {
                    list($bidder, $bid) = $tuple;

                    $this->conn->insert('auction_bids', [
                        'auction_id' => $auction->getId(),
                        'user_id' => $bidder->getId(),
                        'amount' => $bid->getAmount()->getAmount(),
                        'currency' => $bid->getAmount()->getCurrency(),
                        'original_amount' => $bid->getOriginalAmount()->getAmount(),
                        'original_currency' => $bid->getOriginalAmount()->getCurrency(),
                    ]);
                }
            });
        }
    }

    protected function rowsToAuctions($rows)
    {
        $groupedRows = [];
        $auctions = [];

        foreach($rows as $row) {
            if (!isset($groupedRows[$row['id']])) {
                $groupedRows[$row['id']] = [];
            }

            $groupedRows[$row['id']][] = $row;
        }

        foreach ($groupedRows as $id => $rows) {

            if (isset($this->identityMap[$id])) {
                $auctions[] = $this->identityMap[$id];
                continue;
            }

            $row = reset($rows);
            $auction = new Entity\Auction(
                $id,
                $row['name'],
                new \DateTime($row['ending_at']),
                new Currency($row['currency'])
            );

            $this->identityMap[$id] = $auction;
            $this->originalData[$id] = array(
                'auction' => $rows
            );
            $auctions[] = $auction;

            foreach ($rows as $row) {
                if (!empty($row['bid_id'])) {
                    $amount = new Money((int) $row['bid_amount'], new Currency($row['bid_currency']));
                    $originalAmount = new Money((int) $row['bid_original_amount'], new Currency($row['bid_original_currency']));

                    $bid = new Bid($amount, $originalAmount);

                    /* could look to proxy this */
                    $bidder = $this->userRepo->find($row['bid_user_id']);

                    $auction->addBid($bidder, $bid);
                }
            }

            $this->originalData[$id]['bids'] = $auction->getBids();
        }

        return $auctions;
    }

    public function createAuction($name, $endsAt)
    {
        $sql = sprintf("INSERT INTO auctions (name, ending_at, currency) VALUES ('%s', '%s', '%s')",
            $name,
            $endsAt->format('Y-m-d'),
            'GBP'
        );

        $this->conn->executeQuery($sql);
    }
}
