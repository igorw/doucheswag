<?php

namespace tests\integration\Douche\Storage\Sql;

use Doctrine\DBAL\Schema\Table;

class SqlTestCase extends \PHPUnit_Framework_TestCase 
{
    protected $conn;

    public function setup()
    {
        $params = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $this->conn = \Doctrine\DBAL\DriverManager::getConnection($params);
        
        $sm = $this->conn->getSchemaManager();

        $auctions = new \Doctrine\DBAL\Schema\Table("auctions");
        $auctions->addColumn('id', 'integer');
        $auctions->setPrimaryKey(array("id"));
        $auctions->addColumn('name', 'string');
        $auctions->addColumn('ending_at', 'datetime');
        $auctions->addColumn('currency', 'string');
        $sm->createTable($auctions);

        $auctionBids = new \Doctrine\DBAL\Schema\Table("auction_bids");
        $auctionBids->addColumn('id', 'integer');
        $auctionBids->setPrimaryKey(array("id"));
        $auctionBids->addColumn('auction_id', 'integer');
        $auctionBids->addColumn('user_id', 'string');
        $auctionBids->addColumn('amount', 'integer');
        $auctionBids->addColumn('currency', 'string');
        $auctionBids->addColumn('original_amount', 'integer');
        $auctionBids->addColumn('original_currency', 'string');
        $sm->createTable($auctionBids);
    }
}
