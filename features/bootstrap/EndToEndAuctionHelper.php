<?php

use Doctrine\DBAL\Connection;
use Behat\Mink\Mink;

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class EndToEndAuctionHelper
{
    protected $userHelper;
    protected $conn;
    protected $mink;
    protected $auctionId;

    public function __construct(UserHelper $userHelper, Connection $conn, Mink $mink)
    {
        $this->userHelper = $userHelper;
        $this->conn = $conn;
        $this->mink = $mink;
    }

    public function createAuction($name, $endingAt = null)
    {
        $endingAt = $endingAt ?: new \DateTime("+10 days");

        $this->conn->insert('auctions', [
            'name' => $name,
            'ending_at' => $endingAt->format("Y-m-d H:i:s"),
            'currency' => 'USD',
        ]);

        $this->auctionId = $this->conn->lastInsertId();
        $this->auctionName = $name;
    }

    public function truncateAuctions()
    {
        $this->conn->query("TRUNCATE auctions");
    }

    public function viewAuction()
    {
        $this->mink->getSession()->visit("/auction/" . $this->auctionId);
    }

    public function assertAuctionPresent()
    {
        $this->mink->assertSession()->statusCodeEquals(200);
        $this->mink->assertSession()->pageTextContains($this->auctionName);
    }

    protected function getUserHelper()
    {
        return $this->userHelper;
    }
}
