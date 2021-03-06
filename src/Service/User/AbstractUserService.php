<?php

namespace App\Service\User;

use App\Dto\User\UserAccountOutput;
use App\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractUserService
{
    private UserPasswordHasherInterface $passwordHasher;
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EventDispatcherInterface $eventDispatcher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->eventDispatcher = $eventDispatcher;
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
        $userProfile = $user->getUserProfile();

        $userOutputDto = new UserAccountOutput();
        $userOutputDto->email = $user->getEmail();
        $userOutputDto->name = $userProfile->getName();
        $userOutputDto->city = $userProfile->getCity();

        return $userOutputDto;
    }

    public function isUserActive(User $user): bool
    {
        return $user->getIsConfirmed() && $user->getIsEnabled();
    }
}
