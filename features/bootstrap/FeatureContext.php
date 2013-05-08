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
use Douche\Repository\UserArrayRepository;
use Douche\View\AuctionView;

require_once __DIR__ . '/AuctionHelper.php';

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class FeatureContext extends BehatContext
{
    public function __construct(array $parameters)
    {
        $this->users = [
            'igorw' => new User('igorw', 'Igor Wiedler', 'igor@wiedler.ch', 'BAR'),
        ];

        $this->rawUsers = [
            'davedevelopment' => [
                'id'        => 'davedevelopment',
                'name'      => 'Dave Marshall',
                'email'     => 'dave.marshall@atstsolutions.co.uk',
                'password'  => 'foo',
            ],
        ];

        $this->userRepository = new UserArrayRepository(array_values($this->users));

        $this->userHelper    = new UserHelper($this->userRepository);
        $this->auctionHelper = new AuctionHelper($this->userHelper);
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
        $this->userHelper->createUser();
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
        $this->auctionHelper->placeBid(1.0);
    }

    /**
     * @When /^I place a bid on the running auction in a different currency$/
     */
    public function iPlaceABidOnTheRunningAuctionInADifferentCurrency()
    {
        $this->auctionHelper->placeBidWithAlternateCurrency(1.0);
    }

    /**
     * @When /^I place a bid of "([^"]+)" on the auction$/
     */
    public function iPlaceABidOfXXXOnTheRunningAuction($amount)
    {
        $this->auctionHelper->placeBid($amount);
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

    /**
     * @Given /^I am an anonymous user$/
     */
    public function iAmAnAnonymousUser()
    {
        $this->userHelper->iAmAnonymous();
    }

    /**
     * @When /^I register a new user account as "([^"]*)"$/
     */
    public function iRegisterANewUserAccount($userId)
    {
        $userData = $this->rawUsers[$userId];
        $this->userHelper->registerUserAccount($userData);
    }

    /**
     * @Then /^I should see my account "([^"]*)" was created$/
     */
    public function iShouldSeeMyAccountWasCreated($userId)
    {
        $this->userHelper->assertUserCreated($userId);
    }
}
