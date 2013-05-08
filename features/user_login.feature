Feature: User Login

    Scenario: User logs in
        Given I am a registered user
        When I login
        Then I should be logged in
