<?php

namespace tests\integration\Douche\Storage\Sql;

use Doctrine\DBAL\Schema\Table;
use Douche\Storage\Sql\Util;

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

        Util::createAuctionSchema($this->conn);
    }
}
