<?php

namespace App\Controller\Api;

use App\Dto\User\UserAccountOutput;
use App\Dto\User\UserResetPasswordInput;
use App\Service\User\AbstractUserService;
use App\Service\User\UserAccountService;
use Doctrine\ORM\EntityManagerInterface;

class UserResetPasswordController extends AbstractUserController
{
    private UserAccountService $userAccountService;

    public function __construct(UserAccountService $userAccountService, EntityManagerInterface $entityManager)
    {
        $this->userAccountService = $userAccountService;

        parent::__construct($entityManager);
    }

    public function __invoke(UserResetPasswordInput $data): UserAccountOutput
    {
        $user = $this->getUserByPropertyAndValue('passwordResetToken', $data->passwordResetToken);

        $this->userAccountService->resetPassword($user, $data->newPlainPassword);

        return AbstractUserService::createUserOutputDto($user);
    }
}