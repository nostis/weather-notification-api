<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordResetRequestInput
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max=255)
     */
    public string $email;
}