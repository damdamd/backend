<?php

namespace App\App\Command;

use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\Repository\VehicleRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class ParkVehicle
{
    public function __construct(
        private LocationRepositoryInterface $locationRepository,
        private VehicleRepositoryInterface  $vehicleRepository
    )
    {
    }

    public function __invoke(ParkVehicleCommand $command): void
    {
        $location = $this->locationRepository->findByLocationId($command->locationId);
        $vehicle = $this->vehicleRepository->getByPlateNumber($command->vehiclePlateNumber);

        $vehicle->park($location);

        $this->vehicleRepository->save($vehicle);
    }
}