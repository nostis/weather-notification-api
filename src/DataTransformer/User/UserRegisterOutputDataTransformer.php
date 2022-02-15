<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\User\UserAccountOutput;
use App\Entity\User;
use App\Service\User\UserRegisterService;

class UserRegisterOutputDataTransformer implements DataTransformerInterface
{
    private UserRegisterService $userRegisterService;

    public function __construct(UserRegisterService $userRegisterService)
    {
        $this->userRegisterService = $userRegisterService;
    }

    /**
     * @param User $data
     */
    public function transform($data, string $to, array $context = [])
    {
        $userOutputDto = $this->userRegisterService->createUserOutputDto($data);

        return $userOutputDto;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return UserAccountOutput::class === $to && $data instanceof User;
    }
}