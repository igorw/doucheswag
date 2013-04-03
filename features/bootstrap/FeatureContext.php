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

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class FeatureContext extends BehatContext
{
    public function __construct(array $parameters)
    {
    }

    /**
     * @Given /^there are no running auctions$/
     */
    public function thereAreNoRunningAuctions()
    {
        $this->auctions = [];
    }

    /**
     * @When /^I list the running auctions$/
     */
    public function iListTheRunningAuctions()
    {
        $repo = new AuctionArrayRepository($this->auctions);
        $interactor = new AuctionList($repo);
        $this->response = $interactor();
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
        $this->auctions = [
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
