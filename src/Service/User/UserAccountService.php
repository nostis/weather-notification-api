<?php

namespace App\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotActiveException;
use App\Exception\User\UserAlreadyActivatedException;
use App\Service\Mail\Mailer;
use App\Service\Mail\MailFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAccountService extends AbstractUserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, MailFactory $mailFactory, Mailer $mailer)
    {
        $this->entityManager = $entityManager;

        parent::__construct($userPasswordHasher, $mailFactory, $mailer);
    }

    public function resetPassword(User $user, string $newPlainPassword): void
    {
        $user->setPasswordResetToken(null);
        $user->setPassword($this->getHashedPassword($user, $newPlainPassword));

        $this->entityManager->flush();

        //handle mail - dispatch event?
    }

    public function requestPasswordReset(User $user): void
    {
        if($this->isUserNotActive($user)) {
            throw new UserNotActiveException();
        }

        $user->setPasswordResetToken($this->getRandomString());

        $this->entityManager->flush();

        //handle mail - dispatch event?
    }

    public function confirmAccount(User $user): void
    {
        if($this->isUserAlreadyConfirmed($user)) {
            throw new UserAlreadyActivatedException();
        }

        $user->setIsConfirmed(true);
        $user->setIsEnabled(true);

        $this->entityManager->flush($user);
    }

    private function isUserNotActive(User $user): bool
    {
        if(!$this->isUserActive($user)) { //user not found or not active
            return true;
        }

        return false;
    }

    private function isUserAlreadyConfirmed(User $user): bool
    {
        return $user->getIsConfirmed();
    }
}