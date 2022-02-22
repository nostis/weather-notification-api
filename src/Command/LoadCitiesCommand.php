<?php

namespace App\Command;

use App\Entity\City;
use App\Service\City\CityLoaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-cities',
    description: 'Load cities from loader to db',
)]
class LoadCitiesCommand extends Command
{
    private CityLoaderInterface $cityLoader;
    private EntityManagerInterface $entityManager;

    public function __construct(CityLoaderInterface $cityLoader, EntityManagerInterface $entityManager, string $name = null)
    {
        $this->cityLoader = $cityLoader;
        $this->entityManager = $entityManager;

        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $cities = $this->cityLoader->getLoadedCities();

        /**
         * @var City $city
         */
        foreach($cities as $city) {
            $this->entityManager->persist($city);
        }

        $this->entityManager->flush();

        $io->success('Successfully loaded cities to db');

        return Command::SUCCESS;
    }
}
