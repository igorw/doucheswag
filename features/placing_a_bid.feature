Feature: Placing a bid on an auction

    Scenario: Place bid on a running auction
        Given there is a running auction
        And I am a registered user
        And I am viewing the auction
        When I place a bid on the running auction
        Then I should see my bid is accepted

    Scenario: Place a bid on an auction in a different currency
        Given there is a running auction
        And I am a registered user
        And I am viewing the auction
        When I place a bid on the running auction in a different currency
        Then I should see my bid is accepted
        And I should see the amount placed in the auction currency

    Scenario: Place a bid below the required minimum
        Given there is a running auction
        And the auction has a high bid of "10.00"
        And I am a registered user
        And I am viewing the auction
        When I place a bid of "9.00" on the auction
        Then I should see my bid is rejected

    Scenario: Place a bid on a closed auction
        Given there is a closed auction
        And I am a registered user
        And I am viewing the auction
        When I place a bid on the auction
        Then I should see my bid is rejected
