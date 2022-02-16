<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordResetRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max=255)
     */
    public string $email;
}