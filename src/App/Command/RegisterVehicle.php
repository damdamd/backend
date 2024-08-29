<?php

namespace App\App\Command;

use App\Domain\Repository\FleetRepositoryInterface;
use App\Domain\Repository\VehicleRepositoryInterface;
use DomainException;

readonly class RegisterVehicle
{
    public function __construct(
        private FleetRepositoryInterface   $fleetRepository,
        private VehicleRepositoryInterface $vehicleRepository
    )
    {
    }

    public function __invoke(RegisterVehicleCommand $command): void
    {
        $fleet = $this->fleetRepository->getByFleetId($command->fleetId);

        if($fleet->contains($command->vehiclePlateNumber)){
            throw new DomainException('Vehicle has already been registered');
        }

        $vehicle = $this->vehicleRepository->getByPlateNumber($command->vehiclePlateNumber);

        $fleet->addVehicle($vehicle);

        $this->fleetRepository->update($fleet);
    }
}