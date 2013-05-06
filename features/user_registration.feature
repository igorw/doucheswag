Feature: User registration

    Scenario: Registering a new user account
        Given I am an anonymous user
        When I register a new user account as "davedevelopment"
        Then I should see my account "davedevelopment" was created
