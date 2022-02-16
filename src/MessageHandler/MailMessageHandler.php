<?php

namespace App\MessageHandler;

use App\Entity\Mail\Mail;
use App\Message\MailMessage;
use App\Service\Mail\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MailMessageHandler
{
    private EntityManagerInterface $entityManager;
    private Mailer $mailer;

    public function __construct(EntityManagerInterface $entityManager, Mailer $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    public function __invoke(MailMessage $mailMessage)
    {
        $mail = $this->entityManager->getRepository(Mail::class)->find($mailMessage->getMailId());

        if($this->isMailAlreadySent($mail)) {
            return;
        }

        $this->mailer->forceSend($mail);
    }

    private function isMailAlreadySent(Mail $mail): bool
    {
        return $mail->getSentAt() != null;
    }
}