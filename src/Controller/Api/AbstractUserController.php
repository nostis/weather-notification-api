<?php

namespace App\Controller\Api;

use ApiPlatform\Core\Exception\PropertyNotFoundException;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

abstract class AbstractUserController extends AbstractController
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
            throw new PropertyNotFoundException();
        }

        if($user == null) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}