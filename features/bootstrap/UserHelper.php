<?php

use Douche\Entity\UserRepository;
use Douche\Entity\User;
use Douche\Interactor\RegisterUser;
use Douche\Interactor\RegisterUserRequest;
use Douche\Service\UppercasePasswordEncoder;

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class UserHelper
{
    private $userRepo;
    private $passwordEncoder;
    private $response;
    private $user;

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

    public function createUser()
    {
        $user = new User(uniqid(), uniqid(), uniqid().'@'.uniqid().'.com', uniqid());
        $this->getUserRepository()->add($user);
        $this->user = $user;
        return $user->getId();
    }

    public function getCurrentUserId()
    {
        return isset($this->user) ? $this->user->getId() : null;
    }

    public function iAmAnonymous()
    {
        $this->user = null;
    }

    public function getUserRepository()
    {
        return $this->userRepo;
    }

    private function getPasswordEncoder()
    {
        $this->passwordEncoder = $this->passwordEncoder ?: new UppercasePasswordEncoder();

        return $this->passwordEncoder;
    }
}
