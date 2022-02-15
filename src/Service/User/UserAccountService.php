<?php

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\WrongConfirmationTokenException;
use Doctrine\ORM\EntityManagerInterface;

class UserAccountService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function confirmAccount(string $confirmationToken): void
    {
        $foundUser = $this->entityManager->getRepository(User::class)->findUserByConfirmationToken($confirmationToken);

        if($this->isFoundUserNotValid($foundUser)) {
            throw new WrongConfirmationTokenException( );
        }

        $foundUser->setIsConfirmed(true);
        $foundUser->setIsEnabled(true);

        $this->entityManager->flush($foundUser);
    }

    private function isFoundUserNotValid(?User $user): bool
    {
        if($user == null || $user->getIsConfirmed()) { //user not found or already activated
            return true;
        }

        return false;
    }
}