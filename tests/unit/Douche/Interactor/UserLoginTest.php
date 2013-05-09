<?php

namespace Douche\Interactor;

use Douche\Interactor\UserLogin;
use Douche\Interactor\UserLoginRequest;
use Douche\Interactor\UserLoginResponse;
use Douche\Entity\User;
use Phake;

class UserLoginTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->userRepo = Phake::mock('Douche\Entity\UserRepository');
        $this->passwordEncoder = Phake::mock('Douche\Interactor\PasswordEncoder');
        $this->interactor = new UserLogin($this->userRepo, $this->passwordEncoder);

        Phake::when($this->userRepo)->findOneByEmail('dave@example.com')->thenReturn(
            $this->sampleUser = new User('dave', 'Dave', 'dave@example.com', 'encoded password')
        );

        Phake::when($this->passwordEncoder)->isPasswordValid(
            $this->sampleUser->getPasswordHash(),
            $this->correctPassword = 'password'
        )->thenReturn(true);
    }

    /** @test */
    public function shouldReturnUserLoginResponse()
    {
        $request = new UserLoginRequest([
            'email' => $this->sampleUser->getEmail(),
            'password' => $this->correctPassword,
        ]);

        $response = call_user_func($this->interactor, $request);
        $this->assertInstanceOf("Douche\Interactor\UserLoginResponse", $response);
    }

    /** @test */
    public function shouldIncludeUserInUserLoginResponse()
    {
        $request = new UserLoginRequest([
            'email' => $this->sampleUser->getEmail(),
            'password' => $this->correctPassword,
        ]);

        $response = call_user_func($this->interactor, $request);
        $this->assertInstanceOf("Douche\View\UserView", $response->user);
    }

    /** 
     * @test 
     * @expectedException Douche\Exception\IncorrectPasswordException
     */
    public function shouldThrowOnIncorrectPassword()
    {
        $request = new UserLoginRequest([
            'email' => $this->sampleUser->getEmail(),
            'password' => "Some wrong password",
        ]);

        $response = call_user_func($this->interactor, $request);
    }

    /** 
     * @test 
     * @expectedException Douche\Exception\UserNotFoundException
     */
    public function shouldThrowIfUserNotFound()
    {
        $request = new UserLoginRequest([
            'email' => 'unknown@example.com',
            'password' => $this->correctPassword,
        ]);

        $response = call_user_func($this->interactor, $request);
    }
}
