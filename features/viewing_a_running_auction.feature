Feature: Viewing a running auction

    @end-to-end-available
    Scenario: Viewing a running auction
        Given there is a running auction
        When I view the running auction
        Then I should see the running auction

