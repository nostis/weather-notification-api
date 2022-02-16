<?php

namespace App\Service\User;

use App\Dto\User\UserAccountOutput;
use App\Entity\User;
use App\Service\Mail\Mailer;
use App\Service\Mail\MailFactory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractUserService
{
    private UserPasswordHasherInterface $passwordHasher;
    protected MailFactory $mailFactory;
    protected Mailer $mailer;

    public function __construct(UserPasswordHasherInterface $passwordHasher, MailFactory $mailFactory, Mailer $mailer)
    {
        $this->passwordHasher = $passwordHasher;
        $this->mailFactory = $mailFactory;
        $this->mailer = $mailer;
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