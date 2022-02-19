<?php

namespace App\Command;

use App\Entity\Mail\Mail;
use App\Message\MailMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:retry-mail-delivery',
    description: 'Add a short description for your command',
)]
class RetryMailDeliveryCommand extends Command
{
    private const MAX_RETRY_COUNT = 3;
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $messageBus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $messageBus, string $name = null)
    {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;

        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $failedMails = $this->getFailedMails();

        /**
         * @var Mail $mail
         */
        foreach($failedMails as $mail) {
            $message = new MailMessage($mail->getId());

            $this->messageBus->dispatch($message);
        }

        $io->success('Successfully dispatched failed mails to worker');

        return Command::SUCCESS;
    }

    private function getFailedMails(): array
    {
        return $this->entityManager->getRepository(Mail::class)->createQueryBuilder('mail')
            ->where('mail.sentAt IS NULL')
            ->andWhere('mail.errorMessage IS NOT NULL')
            ->andWhere('mail.retryCount < :maxRetryCount')
            ->setParameter('maxRetryCount', self::MAX_RETRY_COUNT)
            ->getQuery()->getResult()
            ;
    }
}
