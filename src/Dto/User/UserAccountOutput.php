<?php

namespace App\Dto\User;

use App\Entity\City;

final class UserAccountOutput
{
    public string $email;

    public string $name;

    public City $city;
}
