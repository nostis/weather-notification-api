<?php

namespace App\Repository;

use App\Entity\EmailNotificationChannel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailNotificationChannel|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailNotificationChannel|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailNotificationChannel[]    findAll()
 * @method EmailNotificationChannel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailNotificationChannelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailNotificationChannel::class);
    }
}
