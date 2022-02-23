<?php

namespace App\Controller\Api;

use App\Dto\User\UserAccountOutput;
use App\Dto\User\UserConfirmAccountInput;
use App\Service\User\UserAccountService;
use Doctrine\ORM\EntityManagerInterface;

class ConfirmAccountController extends AbstractUserController
{
    private UserAccountService $userAccountService;

    public function __construct(EntityManagerInterface $entityManager, UserAccountService $userAccountService)
    {
        $this->userAccountService = $userAccountService;

        parent::__construct($entityManager);
    }

    public function __invoke(UserConfirmAccountInput $data): UserAccountOutput
    {
        $user = $this->getUserByPropertyAndValue('accountConfirmationToken', $data->confirmationToken);

        $this->userAccountService->confirmAccount($user);

        return UserAccountService::createUserOutputDto($user);
    }
}
