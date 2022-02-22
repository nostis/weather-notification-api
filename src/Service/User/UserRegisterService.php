<?php

namespace App\Service\User;

use App\Entity\City;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Event\UserAccountCreatedEvent;
use App\Service\NotificationChannel\NotificationChannelService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegisterService extends AbstractUserService
{
    private NotificationChannelService $notificationChannelService;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EventDispatcherInterface $eventDispatcher, NotificationChannelService $notificationChannelService)
    {
        $this->notificationChannelService = $notificationChannelService;

        parent::__construct($passwordHasher, $eventDispatcher);
    }

    public function createUser(string $email, string $plainPassword, string $name, City $city): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->getHashedPassword($user, $plainPassword));
        $user->setCity($city);
        $user->setAccountConfirmationToken($this->getRandomString());

        $userProfile = $this->createUserProfile($user, $name);
        $user->setUserProfile($userProfile);

        $emailNotificationChannel = $this->notificationChannelService->createEmailNotificationChannel($user);
        $user->setEmailNotificationChannel($emailNotificationChannel);

        $this->eventDispatcher->dispatch(new UserAccountCreatedEvent($user), UserAccountCreatedEvent::NAME);

        return $user;
    }

    private function createUserProfile(User $user, string $name): UserProfile
    {
        $userProfile = new UserProfile();
        $userProfile->setName($name);
        $userProfile->setUserRelation($user);

        return $userProfile;
    }

}
