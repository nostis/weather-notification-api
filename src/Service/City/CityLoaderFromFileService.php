<?php

namespace App\Service\City;

use App\Entity\City;
use App\Exception\FailedToAccessDataException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class CityLoaderFromFileService implements CityLoaderInterface
{
    private const FILE_PATH = 'resource/cities.csv';

    private DecoderInterface $decoder;

    public function __construct(DecoderInterface $decoder)
    {
        $this->decoder = $decoder;
    }

    public function getLoadedCities(): Collection
    {
        if($this->isCitiesFileNotExists()) {
            throw new FileNotFoundException('Cities file not found');
        }

        $citiesData = $this->getDecodedCityData();
        $citiesToReturn = new ArrayCollection();

        foreach($citiesData as $item) {
            try {
                $city = new City();
                $city->setName($item['city']);
                $city->setLat($item['lat']);
                $city->setLng($item['lng']);

                $citiesToReturn->add($city);
            } catch (\Exception $exception) {
                throw new FailedToAccessDataException();
            }
        }

        return $citiesToReturn;
    }

    private function isCitiesFileNotExists(): bool
    {
        return !file_exists(self::FILE_PATH);
    }

    private function getDecodedCityData(): array
    {
        $rawData = file_get_contents(self::FILE_PATH);

        return $this->decoder->decode($rawData, CsvEncoder::FORMAT);
    }
}
