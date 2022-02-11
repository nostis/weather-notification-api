<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\User\UserRegisterInput;
use App\Entity\User;
use App\Service\User\UserRegisterService;

class UserRegisterInputDataTransfomer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private UserRegisterService $userRegisterService;

    public function __construct(ValidatorInterface $validator, UserRegisterService $userRegisterService)
    {
        $this->validator = $validator;
        $this->userRegisterService = $userRegisterService;
    }

    /**
     * @param UserRegisterInput $object
     */
    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        return $this->userRegisterService->createUser($object->email, $object->plainPassword, $object->name);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            return false;
        }

        return User::class === $to && null !== ($context['input']['class'] ?? null);
    }
}