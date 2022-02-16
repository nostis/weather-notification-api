<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;

class UserResetPasswordInput
{
    /**
     * @Assert\NotBlank()
     */
    public string $passwordResetToken;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=255)
     * @CustomAssert\StrongPassword()
     */
    public string $newPlainPassword;
}