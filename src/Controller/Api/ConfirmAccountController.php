<?php

namespace App\Controller\Api;

use App\Dto\User\UserConfirmAccountInput;
use App\Service\User\UserAccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfirmAccountController extends AbstractController
{
    private UserAccountService $userAccountService;

    public function __construct(UserAccountService $userAccountService)
    {
        $this->userAccountService = $userAccountService;
    }

    public function __invoke(UserConfirmAccountInput $data): UserConfirmAccountInput
    {
        $this->userAccountService->confirmAccount($data->confirmationToken);

        return $data;
    }
}