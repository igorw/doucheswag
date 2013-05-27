<?php

use Behat\Mink\Mink;

use Douche\Repository\UserRepository;

require_once __DIR__.'/UserHelper.php';

class EndToEndUserHelper extends UserHelper
{
    public function __construct(UserRepository $userRepo, Mink $mink)
    {
        parent::__construct($userRepo);
        $this->mink = $mink;
    }

    public function login()
    {
        $this->mink->getSession()->visit("/login");

        $page = $this->mink->getSession()->getPage();

        $page->fillField('email', $this->getUser()->getEmail());
        $page->fillField('password', $this->userPassword);

        $page->pressButton("Login");
    }

    public function assertSuccessfulLogin()
    {
        $this->mink->assertSession()->statusCodeEquals(200);
        $this->mink->assertSession()->pageTextContains("Logged in as: " . $this->getUser()->getId());
    }
}
