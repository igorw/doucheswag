Feature: Viewing a closed auction

    Scenario: Viewing a closed auction
        Given there is a closed auction
        When I view the running auction
        Then I should see the running auction

    @end-to-end-available
    Scenario: Closed auction does not offer chance to bid
        Given there is a closed auction
        And I am a registered user
        And I am logged in
        When I view the running auction
        Then I should not be offered a chance to bid

