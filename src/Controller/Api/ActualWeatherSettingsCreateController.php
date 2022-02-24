<?php

namespace App\Controller\Api;

use App\Entity\ActualWeatherSettings;
use App\Entity\User;

class ActualWeatherSettingsCreateController extends ExtendedAbstractController
{
    public function __invoke(ActualWeatherSettings $data)
    {
        /**
         * @var User $user
         */
        $currentUser = $this->getCheckedUser();

        $data->setUser($currentUser);

        return $data;
    }
}
