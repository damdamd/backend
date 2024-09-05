<?php

namespace App\App\Query;

use App\Domain\Entity\Location;
use App\Domain\Repository\FleetRepositoryInterface;
use App\Domain\Repository\VehicleRepositoryInterface;
use DomainException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
readonly class LocalizeVehicle
{
    public function __construct(
        private VehicleRepositoryInterface $vehicleRepository,
        private FleetRepositoryInterface   $fleetRepository
    )
    {
    }

    public function __invoke(LocalizeVehicleQuery $query): Location
    {
        $vehicle = $this->vehicleRepository->getByPlateNumber($query->vehiclePlateNumber);
        $fleet = $this->fleetRepository->findByFleetId($query->fleetId);

        if (false === $fleet->ownsVehicle($vehicle)) {
            throw new DomainException('This vehicle doesn\'t belongs to this fleet');
        }

        return $vehicle->getLocation();
    }
}