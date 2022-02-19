<?php

namespace App\Service\NotificationChannel;

use App\Entity\EmailNotificationChannel;
use App\Entity\Interface\NotificationChannelInterface;
use App\Entity\User;

class NotificationChannelService
{
    public function createEmailNotificationChannel(User $user): EmailNotificationChannel
    {
        $emailNotificationChannel = new EmailNotificationChannel();
        $emailNotificationChannel = $this->getNotificationChannelWithFilledCommonParts($user, $emailNotificationChannel);
        $emailNotificationChannel->setEmail($user->getEmail());

        return $emailNotificationChannel;
    }

    private function getNotificationChannelWithFilledCommonParts(User $user, NotificationChannelInterface $notificationChannel): NotificationChannelInterface
    {
        $notificationChannel->setUser($user);

        return $notificationChannel;
    }
}
