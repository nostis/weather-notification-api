<?php

namespace App\Service\NotificationChannel;

use App\Entity\Interface\NotificationChannelInterface;
use App\Model\Notification;
use App\Service\Mail\Mailer;
use App\Service\Mail\MailFactory;
use Symfony\Component\Mime\Address;

class EmailNotificationChannelSenderService extends AbstractNotificationChannelSenderService
{
    private MailFactory $mailFactory;
    private Mailer $mailer;

    public function __construct(MailFactory $mailFactory, Mailer $mailer)
    {
        $this->mailFactory = $mailFactory;
        $this->mailer = $mailer;
    }

    protected function send(NotificationChannelInterface $notificationChannel, Notification $notification): void
    {
        $user = $notificationChannel->getUser();

        $mail = $this->mailFactory->createSimpleMail($notification->getSubject(), $notification->getContent(), new Address($user->getEmail()), $user->getUserProfile()->getName());

        $this->mailer->send($mail);
    }
}
