<?php

namespace Douche\Interactor;

use Douche\Entity\User;
use Douche\Repository\UserRepository;
use Douche\Service\PasswordEncoder;

class RegisterUser
{
    private $userRepo;

    public function __construct(UserRepository $userRepo, PasswordEncoder $passwordEncoder)
    {
        $this->userRepo = $userRepo;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(RegisterUserRequest $request)
    {
        $passwordHash = $this->passwordEncoder->encodePassword($request->password);
        $user = new User($request->id, $request->name, $request->email, $passwordHash);

        $this->userRepo->add($user);

        return new RegisterUserResponse($user->getId());
    }
}
