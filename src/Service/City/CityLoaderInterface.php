<?php

namespace App\Service\City;

use App\Entity\City;
use Doctrine\Common\Collections\Collection;

interface CityLoaderInterface
{
    /**
     * @return Collection|City[]
     */
    public function getLoadedCities(): Collection;
}
