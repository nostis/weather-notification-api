<?php

namespace App\Controller\Api;

use App\Dto\User\UserAccountOutput;
use App\Dto\User\UserResetPasswordInput;
use App\Service\User\UserAccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserResetPasswordController extends AbstractController
{
    private UserAccountService $userAccountService;

    public function __construct(UserAccountService $userAccountService)
    {
        $this->userAccountService = $userAccountService;
    }

    public function __invoke(UserResetPasswordInput $data): UserAccountOutput
    {
        $user = $this->userAccountService->getUserWithResettedPassword($data->passwordResetToken, $data->newPlainPassword);

        return $this->userAccountService->createUserOutputDto($user);
    }
}