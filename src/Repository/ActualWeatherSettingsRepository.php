<?php

namespace App\Repository;

use App\Entity\ActualWeatherSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActualWeatherSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActualWeatherSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActualWeatherSettings[]    findAll()
 * @method ActualWeatherSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActualWeatherSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActualWeatherSettings::class);
    }
}
