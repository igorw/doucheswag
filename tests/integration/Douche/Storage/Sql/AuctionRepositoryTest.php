<?php

namespace tests\integration\Douche\Storage\Sql;

use Douche\Storage\Sql\AuctionRepository;
use Douche\Repository\UserArrayRepository;
use Douche\Entity\User;
use Money\Currency;

class AuctionRepositoryTest extends SqlTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->repo = new AuctionRepository(
            $this->conn,
            $this->userRepo = new UserArrayRepository([])
        );
    }

    /** @test */
    public function findShouldReturnAnExistingAuction()
    {
        $this->createAuction();
        $this->createAuction();
        $id =  $this->createAuction([
            'name' => 'Dave',
            'ending_at' => '2011-10-10 10:10:10',
            'currency' => 'GBP',
        ]);

        $auction = $this->repo->find($id);

        $this->assertInstanceOf("Douche\Entity\Auction", $auction);
        $this->assertEquals($id, $auction->getId());
        $this->assertEquals('Dave', $auction->getName());
        $this->assertEquals(new Currency('GBP'), $auction->getCurrency());
    }  

    /** @test */
    public function findShouldJoinBids()
    {
        $this->createAuction();
        $this->createAuction();

        $userId = $this->createUser();
        $id =  $this->createAuction([
            'name' => 'Dave',
            'ending_at' => '2011-10-10 10:10:10',
            'currency' => 'GBP',
            'bids' => [
                [
                    'amount' => 200,
                    'currency' => 'GBP',
                    'original_amount' => 600,
                    'original_currency' => 'USD',
                ],
                [
                    'amount' => 300,
                    'currency' => 'GBP',
                    'original_amount' => 900,
                    'original_currency' => 'USD',
                    'user_id' => $userId,
                ],
            ]
        ]);

        $auction = $this->repo->find($id);

        $bid = $auction->getHighestBid();
        $this->assertInstanceOf("Douche\Value\Bid", $bid);
        $this->assertEquals(300, $bid->getAmount()->getAmount());
        $this->assertEquals(new Currency('GBP'), $bid->getAmount()->getCurrency());
        
        $bidder = $auction->getHighestBidder();
        $this->assertInstanceOf("Douche\Entity\User", $bidder);
        $this->assertEquals($userId, $bidder->getId());
    }

    /** @test */
    public function findShouldReturnNullOnEmptyRepo()
    {
        $this->assertNull($this->repo->find(123));
    }

    /** @test */
    public function findShouldReturnNullForUnkownId()
    {
        $id = $this->createAuction();
        $this->assertNull($this->repo->find($id + 123));
    }

    /** @test */
    public function findAllShouldReturnAnEmptyArrayForAnEmptyRepo()
    {
        $this->assertEquals([], $this->repo->findAll());
    }

    /** @test */
    public function findAllShouldReturnArrayOfAuctionsIfExistingAuctions()
    {
        $this->createAuction();
        $this->createAuction();

        $auctions = $this->repo->findAll();

        $this->assertInternalType("array", $auctions);
        foreach ($auctions as $auction) {
            $this->assertInstanceOf("Douche\Entity\Auction", $auction);
        }
    }

    protected function createAuction(array $data = [])
    {
        $data = array_merge([
            'name' => uniqid(),
            'ending_at' => (new \DateTime("+3 days"))->format("Y-m-d H:i:s"),
            'currency' => 'USD',
            'bids' => [
                [
                    'amount' => 2000,
                    'currency' => 'GBP',
                    'original_amount' => 6000000,
                    'original_currency' => 'USD',
                ],
            ]
        ], $data);

        $bids = $data['bids'];
        unset($data['bids']);

        $this->conn->insert('auctions', $data);
        $id = $this->conn->lastInsertId();

        foreach ($bids as $bid) {
            if (!isset($bid['user_id'])) {
                $bid['user_id'] = $this->createUser();
            }            

            $bid['auction_id'] = $id;

            $this->conn->insert('auction_bids', $bid);
        }

        return $id;
    }

    protected function createUser(array $data = array())
    {
        $name         = isset($data['name']) ? $data['name'] : uniqid();
        $email        = isset($data['email']) ? $data['email'] : uniqid();
        $passwordHash = isset($data['passwordHash']) ? $data['passwordHash'] : uniqid();
        $id           = isset($data['id']) ? $data['id'] : uniqid();

        $user = new User($id, $name, $email, $passwordHash);
        $this->userRepo->add($user);
        return $id;
    }
}

