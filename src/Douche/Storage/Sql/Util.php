<?php

namespace Douche\Storage\Sql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

class Util
{
    public static function createAuctionSchema(Connection $conn)
    {
        $sm = $conn->getSchemaManager();

        $schema = static::getAuctionSchema();
        foreach ($schema->getTables() as $table) {
            $sm->createTable($table);
        }
    }

    public static function getAuctionSchema()
    {
        $schema = new Schema;
        $auctions = $schema->createTable('auctions');
        $auctions->addColumn('id', 'integer');
        $auctions->setPrimaryKey(array("id"));
        $auctions->addColumn('name', 'string');
        $auctions->addColumn('ending_at', 'datetime');
        $auctions->addColumn('currency', 'string');

        $auctionBids = $schema->createTable('auction_bids');
        $auctionBids->addColumn('id', 'integer');
        $auctionBids->setPrimaryKey(array("id"));
        $auctionBids->addColumn('auction_id', 'integer');
        $auctionBids->addColumn('user_id', 'string');
        $auctionBids->addColumn('amount', 'integer');
        $auctionBids->addColumn('currency', 'string');
        $auctionBids->addColumn('original_amount', 'integer');
        $auctionBids->addColumn('original_currency', 'string');

        return $schema;
    }
}
