<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Event\UserAccountCreatedEvent;

class UserRegisterService extends AbstractUserService
{
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

        $this->eventDispatcher->dispatch(new UserAccountCreatedEvent($user), UserAccountCreatedEvent::NAME);

        return $user;
    }
}