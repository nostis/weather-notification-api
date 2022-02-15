<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserConfirmAccountInput
{
    /**
     * @Assert\NotBlank
     */
    public string $confirmationToken;
}