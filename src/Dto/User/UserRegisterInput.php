<?php

namespace App\Dto\User;

use App\Entity\City;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;

final class UserRegisterInput
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max=255)
     * @CustomAssert\UniqueEmail()
     */
    public string $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=255)
     * @CustomAssert\StrongPassword()
     */
    public string $plainPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public string $name;

    /**
     * @Assert\NotBlank()
     */
    public City $city;
}
