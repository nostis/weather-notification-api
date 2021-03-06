<?php

namespace App\Service\User;

use App\Entity\User;
use App\Event\UserPasswordResetRequestEvent;
use App\Exception\User\UserNotActiveException;
use App\Exception\User\UserAlreadyActivatedException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAccountService extends AbstractUserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->entityManager = $entityManager;

        parent::__construct($userPasswordHasher, $eventDispatcher);
    }

    public function resetPassword(User $user, string $newPlainPassword): void
    {
        $user->setPasswordResetToken(null);
        $user->setPassword($this->getHashedPassword($user, $newPlainPassword));

        $this->entityManager->flush();
    }

    public function requestPasswordReset(User $user): void
    {
        if($this->isUserNotActive($user)) {
            throw new UserNotActiveException();
        }

        $user->setPasswordResetToken($this->getRandomString());

        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new UserPasswordResetRequestEvent($user), UserPasswordResetRequestEvent::NAME);
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
        if(!$this->isUserActive($user)) {
            return true;
        }

        return false;
    }

    private function isUserAlreadyConfirmed(User $user): bool
    {
        return $user->getIsConfirmed();
    }
}