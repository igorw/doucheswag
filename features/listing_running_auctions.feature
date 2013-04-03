Feature: Listing running auctions

    Scenario: No auctions running
        Given there are no running auctions
        When I list the running auctions
        Then I should see no running auctions

    Scenario: Some Auctions are running
        Given there are some running auctions
        When I list the running auctions
        Then I should see some running auctions
