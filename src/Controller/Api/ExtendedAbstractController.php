<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class ExtendedAbstractController extends AbstractController
{
    protected function getCheckedUser(): UserInterface
    {
        $user = $this->getUser();

        if($this->isUserCurrentlyNotLoggedIn($user)) {
            throw new AccessDeniedException('User not logged in');
        }

        return $user;
    }

    private function isUserCurrentlyNotLoggedIn(?UserInterface $user)
    {
        return $user == null;
    }
}
