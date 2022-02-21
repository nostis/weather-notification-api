<?php

namespace App\Service\Mail;

use App\Entity\Mail\Mail;
use App\Entity\User;
use Symfony\Component\Mime\Address;
use Twig\Environment;

class MailFactory
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function createSimpleMail(string $subject, string $content, Address $address, ?string $clientName): Mail
    {
        $mail = new Mail();

        $mail->setSubject($subject);
        $mail->setHtmlContent($content);
        $mail->setTo($address->getAddress());
        $mail->setClientName($clientName);

        return $mail;
    }

    public function createUserAccountCreatedMail(User $user): Mail
    {
        $mail = $this->getMailWrappedWithUserData($user);

        $userName = $user->getUserProfile()->getName();

        $mail->setSubject('Confirm your account');
        $mail->setHtmlContent($this->twig->render('mail/user_account_created.html.twig', ['name' => $userName, 'confirmationToken' => $user->getAccountConfirmationToken()]));

        return $mail;
    }

    public function createPasswordResetRequestMail(User $user): Mail
    {
        $mail = $this->getMailWrappedWithUserData($user);

        $userName = $user->getUserProfile()->getName();

        $mail->setSubject('Reset your password');
        $mail->setHtmlContent($this->twig->render('mail/user_password_reset_request.html.twig', ['name' => $userName, 'passwordResetToken' => $user->getPasswordResetToken()]));

        return $mail;
    }

    private function getMailWrappedWithUserData(User $user): Mail
    {
        $userName = $user->getUserProfile()->getName();

        $mail = new Mail();
        $mail->setTo($user->getEmail());
        $mail->setClientName($userName);

        return $mail;
    }
}
