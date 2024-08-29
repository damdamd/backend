<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Enum\VehicleType;
use App\Domain\ValueObject\VehiclePlateNumber;
use DomainException;

class Vehicle
{

    private ?Location $location = null;

    public function __construct(
        private readonly VehiclePlateNumber $vehiclePlateNumber,
        private readonly VehicleType        $vehicleType
    )
    {
    }

    public function getVehiclePlateNumber(): VehiclePlateNumber
    {
        return $this->vehiclePlateNumber;
    }

    public function park(Location $location): void
    {
        if (null !== $this->location && $this->location->equals($location)) {
            throw new DomainException('This vehicle is already parked at this location');
        }
        $this->location = $location;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }
}