<?php

namespace App\Service\User;

use App\Dto\User\UserAccountOutput;
use App\Entity\User;
use App\Entity\UserProfile;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegisterService
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function createUserOutputDto(User $user): UserAccountOutput
    {
        $userOutputDto = new UserAccountOutput();
        $userOutputDto->email = $user->getEmail();
        $userOutputDto->name = $user->getUserProfile()->getName();

        return $userOutputDto;
    }

    public function createUser(string $email, string $plainPassword, string $name): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->getHashedPassword($user, $plainPassword));
        $user->setAccountConfirmationToken($this->getRandomString());

        $userProfile = new UserProfile();
        $userProfile->setName($name);
        $userProfile->setUserRelation($user);

        $user->setUserProfile($userProfile);

        return $user;
    }

    private function getHashedPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }

    private function getRandomString(): string
    {
        $randomUuid = Uuid::uuid4();

        return $randomUuid->toString();
    }
}