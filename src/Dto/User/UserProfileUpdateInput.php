<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserProfileUpdateInput
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public ?string $name = '';
}