<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\User\AbstractUserService;

class MeController extends ExtendedAbstractController
{
    public function __invoke($data)
    {
        /**
         * @var User $user
         */
        $user = $this->getCheckedUser();

        return AbstractUserService::createUserOutputDto($user);
    }
}
