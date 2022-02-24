<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\User\UserProfileUpdateInput;
use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class UserProfileUpdateInputDataTransformer implements DataTransformerInitializerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     *
     * @param UserProfileUpdateInput $object
     */
    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        /**
         * @var User $existingUser
         */
        $existingUser = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        return $this->transformInputToTarget($object, $existingUser);
    }

    public function initialize(string $inputClass, array $context = [])
    {
        /**
         * @var User $existingUser
         */
        $existingUser = $context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? null;

        if (!$existingUser) {
            return new User();
        }

        $userInput = new UserProfileUpdateInput();
        $userInput->name = $existingUser->getUserProfile()->getName();
        $userInput->city = $existingUser->getUserProfile()->getCity();

        return $userInput;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            return false;
        }

        return User::class === $to && null !== ($context['input']['class'] ?? null) && $context['input']['class'] == UserProfileUpdateInput::class;
    }

    private function transformInputToTarget(UserProfileUpdateInput $input, User $target): User
    {
        $userProfile = $target->getUserProfile();

        $userProfile->setName($input->name);
        $userProfile->setCity($input->city);

        return $target;
    }
}
