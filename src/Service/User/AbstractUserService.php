<?php

namespace App\Service\User;

use App\Dto\User\UserAccountOutput;
use App\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractUserService
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    protected function getRandomString(): string
    {
        $randomUuid = Uuid::uuid4();

        return $randomUuid->toString();
    }

    protected function getHashedPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }

    public static function createUserOutputDto(User $user): UserAccountOutput
    {
        $userOutputDto = new UserAccountOutput();
        $userOutputDto->email = $user->getEmail();
        $userOutputDto->name = $user->getUserProfile()->getName();

        return $userOutputDto;
    }

    public function isUserActive(User $user): bool
    {
        return $user->getIsConfirmed() && $user->getIsEnabled();
    }
}