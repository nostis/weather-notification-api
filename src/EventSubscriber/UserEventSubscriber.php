<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\UserAccountCreatedEvent;
use App\Event\UserPasswordResetRequestEvent;
use App\Service\Mail\Mailer;
use App\Service\Mail\MailFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEventSubscriber implements EventSubscriberInterface
{
    private MailFactory $mailFactory;
    private Mailer $mailer;

    public function __construct(MailFactory $mailFactory, Mailer $mailer)
    {
        $this->mailFactory = $mailFactory;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserAccountCreatedEvent::NAME => 'onUserAccountCreated',
            UserPasswordResetRequestEvent::NAME => 'onUserPasswordResetRequest'
        ];
    }

    public function onUserAccountCreated(UserAccountCreatedEvent $event)
    {
        $this->sendUserCreatedMail($event->getUser());
    }

    public function onUserPasswordResetRequest(UserPasswordResetRequestEvent $event)
    {
        $this->sendUserPasswordRequestMail($event->getUser());
    }

    private function sendUserCreatedMail(User $user)
    {
        $mail = $this->mailFactory->createUserAccountCreatedMail($user);

        $this->mailer->send($mail);
    }

    private function sendUserPasswordRequestMail(User $user)
    {
        $mail = $this->mailFactory->createPasswordResetRequestMail($user);

        $this->mailer->send($mail);
    }
}