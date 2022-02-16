<?php

namespace App\Controller\Api;

use App\Dto\User\UserPasswordResetRequestInput;
use App\Service\User\UserAccountService;
use Doctrine\ORM\EntityManagerInterface;;

class UserPasswordResetRequestController extends AbstractUserController
{
    private UserAccountService $userAccountService;

    public function __construct(EntityManagerInterface $entityManager, UserAccountService $userAccountService)
    {
        $this->userAccountService = $userAccountService;

        parent::__construct($entityManager);
    }

    public function __invoke(UserPasswordResetRequestInput $data): UserPasswordResetRequestInput
    {
        $user = $this->getUserByPropertyAndValue('email', $data->email);

        $this->userAccountService->requestPasswordReset($user);

        return $data;
    }
}