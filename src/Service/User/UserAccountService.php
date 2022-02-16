<?php

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\UnableToRequestPasswordReset;
use App\Exception\User\WrongConfirmationTokenException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAccountService extends AbstractUserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->entityManager = $entityManager;

        parent::__construct($userPasswordHasher);
    }

    public function getUserWithResettedPassword(string $passwordResetToken, string $newPlainPassword): User
    {
        $foundUser = $this->entityManager->getRepository(User::class)->findUserByPasswordResetToken($passwordResetToken);

        if($this->isRequestPasswordResetActionUserNotValid($foundUser)) {
            throw new UnableToRequestPasswordReset();
        }

        $foundUser->setPasswordResetToken(null);
        $foundUser->setPassword($this->getHashedPassword($foundUser, $newPlainPassword));

        $this->entityManager->flush();

        //handle mail - dispatch event?

        return $foundUser;
    }

    public function requestPasswordReset(string $email): void
    {
        $foundUser = $this->entityManager->getRepository(User::class)->findUserByEmail($email);

        if($this->isRequestPasswordResetActionUserNotValid($foundUser)) {
            throw new UnableToRequestPasswordReset();
        }

        $foundUser->setPasswordResetToken($this->getRandomString());

        $this->entityManager->flush();

        //handle mail - dispatch event?
    }

    public function confirmAccount(string $confirmationToken): void
    {
        $foundUser = $this->entityManager->getRepository(User::class)->findUserByConfirmationToken($confirmationToken);

        if($this->isConfirmAccountActionUserNotValid($foundUser)) {
            throw new WrongConfirmationTokenException();
        }

        $foundUser->setIsConfirmed(true);
        $foundUser->setIsEnabled(true);

        $this->entityManager->flush($foundUser);
    }

    private function isRequestPasswordResetActionUserNotValid(?User $user): bool
    {
        if($user == null || !$user->getIsConfirmed() || !$user->getIsEnabled()) { //user not found or not activated or disabled
            return true;
        }

        return false;
    }

    private function isConfirmAccountActionUserNotValid(?User $user): bool
    {
        if($user == null || $user->getIsConfirmed()) { //user not found or already activated
            return true;
        }

        return false;
    }
}