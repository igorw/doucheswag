<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Douche\Requestor\InteractorFactory;
use Douche\Entity\Auction;
use Douche\Interactor\AuctionList;
use Douche\Interactor\AuctionListRequest;
use Douche\Interactor\AuctionListResponse;
use Douche\Repository\AuctionArrayRepository;
use Douche\View\AuctionView;

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class FeatureContext extends BehatContext
{
    public function __construct(array $parameters)
    {
    }

    /**
     * @beforeScenario
     */
    public function setupInteractorFactory()
    {
        $this->container = require __DIR__ . "/container.php";
        $this->factory = new InteractorFactory($container);
    }

    /**
     * @Given /^there are no running auctions$/
     */
    public function thereAreNoRunningAuctions()
    {
        $this->container['auctions'] = [];
    }

    /**
     * @When /^I list the running auctions$/
     */
    public function iListTheRunningAuctions()
    {
        $interactor = $this->factory->make("auction_list");
        $request = new AuctionListRequest();
        $this->response = $interactor($request);
    }

    /**
     * @Then /^I should see no running auctions$/
     */
    public function iShouldSeeNoRunningAuctions()
    {
        assertEquals(
            new AuctionListResponse([]),
            $this->response
        );
    }

    /**
     * @Given /^there are some running auctions$/
     */
    public function thereAreSomeRunningAuctions()
    {
        $this->container['auctions'] = [
            new Auction('Swag Hat'),
        ];
    }

    /**
     * @Then /^I should see some running auctions$/
     */
    public function iShouldSeeSomeRunningAuctions()
    {
        assertEquals(
            new AuctionListResponse([
                new AuctionView(['name' => 'Swag Hat']),
            ]),
            $this->response
        );
    }
}
