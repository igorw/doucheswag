<?php

use Douche\Entity\UserRepository;
use Douche\Interactor\RegisterUser;
use Douche\Interactor\RegisterUserRequest;
use Douche\Service\UppercasePasswordEncoder;

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class UserHelper
{
    private $userRepo;
    private $passwordEncoder;
    private $response;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function registerUserAccount(array $userData)
    {
        $interactor = new RegisterUser($this->getUserRepository(), $this->getPasswordEncoder());
        $request = new RegisterUserRequest($userData);
        $this->response = $interactor($request);
    }

    public function assertUserCreated($userId)
    {
        assertInstanceOf("Douche\Interactor\RegisterUserResponse", $this->response);
        assertSame($userId, $this->response->id);
        assertNotNull($this->userRepo->find($userId));
    }

    private function getUserRepository()
    {
        return $this->userRepo;
    }

    private function getPasswordEncoder()
    {
        $this->passwordEncoder = $this->passwordEncoder ?: new UppercasePasswordEncoder();

        return $this->passwordEncoder;
    }
}
