<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\User\AbstractUserService;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class MeController extends AbstractUserController
{
    public function __invoke($data)
    {
        /**
         * @var User | null $user
         */
        $user = $this->getUser();

        if($this->isUserCurrentlyNotLoggedIn($user)) {
            throw new AccessDeniedException('User not logged in');
        }

        return AbstractUserService::createUserOutputDto($user);
    }

    private function isUserCurrentlyNotLoggedIn(?User $user)
    {
        return $user == null;
    }
}
