<?php

namespace App\App\Command;

use App\Domain\Entity\Location;
use App\Domain\Entity\ValueObject\LocationId;
use App\Domain\Repository\LocationRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
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