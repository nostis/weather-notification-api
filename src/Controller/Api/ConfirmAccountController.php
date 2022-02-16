<?php

namespace App\Controller\Api;

use App\Dto\User\UserConfirmAccountInput;
use App\Service\User\UserAccountService;
use Doctrine\ORM\EntityManagerInterface;

class ConfirmAccountController extends AbstractUserController
{
    private EntityManagerInterface $entityManager;
    private UserAccountService $userAccountService;

    public function __construct(EntityManagerInterface $entityManager, UserAccountService $userAccountService)
    {
        $this->userAccountService = $userAccountService;

        parent::__construct($entityManager);
    }

    public function __invoke(UserConfirmAccountInput $data): UserConfirmAccountInput
    {
        $user = $this->getUserByPropertyAndValue('accountConfirmationToken', $data->confirmationToken);

        $this->userAccountService->confirmAccount($user);

        return $data;
    }
}