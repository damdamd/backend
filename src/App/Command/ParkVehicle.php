<?php

namespace App\App\Command;

use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\Repository\VehicleRepositoryInterface;

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
        $location = $this->locationRepository->getByLocationId($command->locationId);
        $vehicle = $this->vehicleRepository->getByPlateNumber($command->vehiclePlateNumber);

        $vehicle->park($location);

        $this->vehicleRepository->save($vehicle);
    }
}