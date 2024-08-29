<?php

namespace App\App\Command;

use App\Domain\Entity\Location;
use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\ValueObject\LocationId;

readonly class CreateLocation
{
    public function __construct(private LocationRepositoryInterface $locationRepository)
    {
    }

    public function __invoke(CreateLocationCommand $command): LocationId
    {
        $location = new Location($command->longitude, $command->latitude);
        $this->locationRepository->create($location);
        return $location->getLocationId();
    }
}