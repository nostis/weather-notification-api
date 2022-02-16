<?php

namespace App\Controller\Api;

use App\Dto\User\UserPasswordResetRequestInput;
use App\Service\User\UserAccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserPasswordResetRequestController extends AbstractController
{
    private UserAccountService $userAccountService;

    public function __construct(UserAccountService $userAccountService)
    {
        $this->userAccountService = $userAccountService;
    }

    public function __invoke(UserPasswordResetRequestInput $data): UserPasswordResetRequestInput
    {
        $this->userAccountService->requestPasswordReset($data->email);

        return $data;
    }
}