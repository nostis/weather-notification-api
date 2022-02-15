<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\User\UserProfileUpdateInput;
use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class UserProfileUpdateInputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param UserProfileUpdateInput $object
     */
    public function transform($object, string $to, array $context = [])
    {
        /**
         * @var User $existingUser
         */
        $existingUser = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        return $this->transformInputToTarget($object, $existingUser);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            return false;
        }

        return User::class === $to && null !== ($context['input']['class'] ?? null);
    }

    private function transformInputToTarget(UserProfileUpdateInput $input, User $target): User
    {
        $userProfile = $target->getUserProfile();

        $userProfile->setName($input->name);

        return $target;
    }
}