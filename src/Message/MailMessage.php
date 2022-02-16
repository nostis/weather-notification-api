<?php

namespace App\Message;

class MailMessage
{
    private int $mailId;

    public function __construct(int $mailId)
    {
        $this->mailId = $mailId;
    }

    public function getMailId(): int
    {
        return $this->mailId;
    }
}