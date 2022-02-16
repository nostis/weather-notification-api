<?php

namespace App\Service\Mail;

use App\Entity\Mail\Mail;
use App\Message\MailMessage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    private EntityManagerInterface $entityManager;
    private ContainerBagInterface $parameters;
    private LoggerInterface $logger;
    private MailerInterface $symfonyMailer;
    private MessageBusInterface $messageBus;

    public function __construct(EntityManagerInterface $entityManager, ContainerBagInterface $parameters, LoggerInterface $logger, MailerInterface $symfonyMailer, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->parameters = $parameters;
        $this->logger = $logger;
        $this->symfonyMailer = $symfonyMailer;
        $this->messageBus = $messageBus;
    }

    public function send(Mail $mail): void
    {
        $this->entityManager->persist($mail);
        $this->entityManager->flush();

        $this->messageBus->dispatch(new MailMessage($mail->getId()));
    }

    public function forceSend(Mail $mail): void
    {
        try {
            $this->symfonyMailer->send($this->createMailFromMailEntity($mail));
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(sprintf('Error when trying to send email: %s', $e->getMessage()));
        }

        $mail->setSentAt(new \DateTimeImmutable());

        $this->entityManager->flush();
    }

    private function createMailFromMailEntity(Mail $mail): Email
    {
        return (new Email())
            ->from($this->parameters->get('app.mail_from')) // or no?
            ->to($mail->getTo())
            ->subject($mail->getSubject())
            ->html($mail->getHtmlContent());
    }
}