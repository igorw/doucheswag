<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Douche\Entity\Auction;
use Douche\Interactor\AuctionList;
use Douche\Interactor\AuctionListResponse;
use Douche\Repository\AuctionArrayRepository;
use Douche\View\AuctionView;

require_once __DIR__ . '/AuctionHelper.php';

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class FeatureContext extends BehatContext
{
    public function __construct(array $parameters)
    {
        $this->auctionHelper = new AuctionHelper();
    }

    /**
     * @Given /^there are no running auctions$/
     */
    public function thereAreNoRunningAuctions()
    {
        $this->auctionHelper->truncateAuctions();
    }

    /**
     * @When /^I list the running auctions$/
     */
    public function iListTheRunningAuctions()
    {
        $this->auctionHelper->listAuctions();
    }

    /**
     * @Then /^I should see no running auctions$/
     */
    public function iShouldSeeNoRunningAuctions()
    {
        $this->auctionHelper->assertNoRunningAuctions();
    }

    /**
     * @Given /^there are some running auctions$/
     */
    public function thereAreSomeRunningAuctions()
    {
        $this->auctionHelper->createAuction("Swag Hat");
    }

    /**
     * @Then /^I should see some running auctions$/
     */
    public function iShouldSeeSomeRunningAuctions()
    {
        $this->auctionHelper->assertSomeRunningAuctions();
    }

    /**
     * @Given /^there is a running auction$/
     */
    public function thereIsARunningAuction()
    {
        $this->auctionHelper->createAuction("Swag Scarf");
    }

    /**
     * @When /^I view the running auction$/
     */
    public function iViewTheRunningAuction()
    {
        $this->auctionHelper->viewAuction();
    }

    /**
     * @Then /^I should see the running auction$/
     */
    public function iShouldSeeTheRunningAuction()
    {
        $this->auctionHelper->assertAuctionPresent();
    }

}
