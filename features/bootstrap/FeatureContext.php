<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Douche\Entity\Auction;
use Douche\Entity\User;
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
        $this->users = [
            'igorw' => new User('igorw'),
        ];
        $this->auctionHelper = new AuctionHelper(array_values($this->users));
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
     * @Given /^there is a (closed|running) auction$/
     */
    public function thereIsAAuction($status)
    {
        $endingAt = $status == 'closed' ? new \DateTime("-1 days") : null;
        $this->auctionHelper->createAuction("Swag Scarf", $endingAt);
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

    /**
     * @Given /^I am a registered user$/
     */
    public function iAmARegisteredUser()
    {
        $this->user = $this->users['igorw'];
    }

    /**
     * @Given /^I am viewing the auction$/
     */
    public function iAmViewingTheAuction()
    {
        $this->auctionHelper->viewAuction();
    }

    /**
     * @When /^I place a bid on the(?:| running) auction$/
     */
    public function iPlaceABidOnTheRunningAuction()
    {
        $this->auctionHelper->placeBid(1.0, $this->user);
    }

    /**
     * @When /^I place a bid on the running auction in a different currency$/
     */
    public function iPlaceABidOnTheRunningAuctionInADifferentCurrency()
    {
        $this->auctionHelper->placeBidWithAlternateCurrency(1.0, $this->user);
    }

    /**
     * @When /^I place a bid of "([^"]+)" on the auction$/
     */
    public function iPlaceABidOfXXXOnTheRunningAuction($amount)
    {
        $this->auctionHelper->placeBid($amount, $this->user);
    }
    
    /**
     * @Then /^I should see my bid is accepted$/
     */
    public function iShouldSeeMyBidIsAccepted()
    {
        $this->auctionHelper->assertBidAccepted();
    }

    /**
     * @Then /^I should see my bid is rejected$/
     */
    public function iShouldSeeMyBidIsRejected()
    {
        $this->auctionHelper->assertBidRejected();
    }

    /**
     * @Given /^the auction has a high bid of "([^"]*)"$/
     */
    public function theAuctionHasAHighBidOf($amount)
    {
        $this->auctionHelper->placeBid($amount);
    }


    /**
     * @Given /^I should see the amount placed in the auction currency$/
     */
    public function iShouldSeeTheAmountPlacedInTheAuctionCurrency()
    {
        $this->auctionHelper->assertBidAcceptedWithCurrencyConversion();
    }

}
