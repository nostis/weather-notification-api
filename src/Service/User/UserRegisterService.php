<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserProfile;

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

        $this->sendUserCreatedMail($user);

        return $user;
    }

    private function sendUserCreatedMail(User $user)
    {
        $mail = $this->mailFactory->createUserAccountCreatedMail($user);

        $this->mailer->send($mail);
    }
}