<?php

namespace App\Service\User;

use Ramsey\Uuid\Uuid;

abstract class AbstractUserService
{
    protected function getRandomString(): string
    {
        $randomUuid = Uuid::uuid4();

        return $randomUuid->toString();
    }
}