<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Exception\User\BadPropertyException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractUserController extends ExtendedAbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; //maybe from container?
    }

    public function getUserByPropertyAndValue(string $property, string $value): User
    {
        try {
            $user = $this->entityManager->getRepository(User::class)->findOneBy([$property => $value]);
        } catch (\Exception $e) {
            throw new BadPropertyException();
        }

        if($user == null) {
            throw new NotFoundHttpException();
        }

        return $user;
    }
}
