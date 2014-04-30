<?php

namespace Douche\Interactor;

use Douche\View\UserView;
use Douche\Exception\IncorrectPasswordException;
use Douche\Exception\UserNotFoundException;
use Douche\Repository\UserRepository;
use Douche\Service\PasswordEncoder;

class UserLogin
{
    private $userRepo;
    private $passwordEncoder;

    public function __construct(UserRepository $userRepo, PasswordEncoder $passwordEncoder)
    {
        $this->userRepo = $userRepo;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(UserLoginRequest $request)
    {
        $user = $this->userRepo->findOneByEmail($request->email);

        if (!$user) {
            throw new UserNotFoundException($request->email);
        }

        if (!$this->passwordEncoder->isPasswordValid($user->getPasswordHash(), $request->password)) {
            throw new IncorrectPasswordException($request->email);
        }

        return new UserLoginResponse(UserView::fromUser($user));
    }
}
