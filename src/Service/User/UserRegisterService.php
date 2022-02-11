<?php

namespace App\Service\User;

use App\Dto\User\UserRegisterOutput;
use App\Entity\User;
use App\Entity\UserProfile;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserRegisterService
{
    private PasswordHasherInterface $passwordHasher;

    public function __construct(PasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function createUserOutputDto(User $user): UserRegisterOutput
    {
        $userOutputDto = new UserRegisterOutput();
        $userOutputDto->email = $user->getEmail();
        $userOutputDto->name = $user->getUserProfile()->getName();

        return $userOutputDto;
    }

    public function createUser(string $email, string $plainPassword, string $name): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->getHashedPassword($plainPassword));
        $user->setAccountConfirmationToken($this->getRandomString());

        $userProfile = new UserProfile();
        $userProfile->setName($name);
        $userProfile->setUserRelation($user);

        $user->setUserProfile($userProfile);

        return $user;
    }

    private function getHashedPassword(string $plainPassword): string
    {
        return $this->passwordHasher->hash($plainPassword);
    }

    private function getRandomString(): string
    {
        return uniqid();
    }
}