<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\User\UserAccountOutput;
use App\Entity\User;
use App\Service\User\AbstractUserService;

class UserRegisterOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param User $data
     */
    public function transform($data, string $to, array $context = [])
    {
        return AbstractUserService::createUserOutputDto($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return UserAccountOutput::class === $to && $data instanceof User;
    }
}