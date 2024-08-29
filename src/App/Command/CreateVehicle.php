<?php

namespace App\App\Command;

use App\Domain\Entity\Vehicle;
use App\Domain\Repository\VehicleRepositoryInterface;

readonly class CreateVehicle
{
    public function __construct(public VehicleRepositoryInterface $vehicleRepository)
    {
    }

    public function __invoke(CreateVehicleCommand $command): void
    {
        $vehicle = new Vehicle($command->vehiclePlateNumber, $command->vehicleType);
        $this->vehicleRepository->save($vehicle);
    }
}