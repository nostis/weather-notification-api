<?php

namespace App\Service\Mail;

use App\Entity\Mail\Mail;
use App\Entity\User;
use Twig\Environment;

class MailFactory
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function createUserAccountCreatedEmail(User $user): Mail
    {
        $userName = $user->getUserProfile()->getName();

        $mail = new Mail();
        $mail->setSubject('Witaj/Hello'); //translation?
        $mail->setTo($user->getEmail());
        $mail->setClientName($userName);
        $mail->setHtmlContent($this->twig->render('mail/user_account_created.html.twig', ['name' => $userName, 'confirmationToken' => $user->getAccountConfirmationToken()]));

        return $mail;
    }
}