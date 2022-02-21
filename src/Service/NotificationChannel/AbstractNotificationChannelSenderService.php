<?php

namespace App\Service\NotificationChannel;

use App\Entity\Interface\NotificationChannelInterface;
use App\Model\Notification;

abstract class AbstractNotificationChannelSenderService
{
    public function sendNotification(NotificationChannelInterface $notificationChannel, Notification $notification): void
    {
        if($this->isChannelNotActive($notificationChannel)) {
            return;
        }

        $this->send($notificationChannel, $notification);
    }

    protected abstract function send(NotificationChannelInterface $notificationChannel, Notification $notification): void;

    private function isChannelNotActive(NotificationChannelInterface $notificationChannel): bool
    {
        return !$notificationChannel->getIsActive();
    }
}
