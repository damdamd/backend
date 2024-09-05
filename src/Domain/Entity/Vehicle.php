<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Enum\VehicleType;
use App\Domain\Entity\ValueObject\VehiclePlateNumber;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Vehicle
{
    #[ORM\OneToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'location_id')]
    private ?Location $location = null;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'VehiclePlateNumber')]
        private readonly VehiclePlateNumber $vehiclePlateNumber,
        #[ORM\Column(enumType: VehicleType::class)]
        protected readonly VehicleType $vehicleType,
    ) {
    }

    public function getVehiclePlateNumber(): VehiclePlateNumber
    {
        return $this->vehiclePlateNumber;
    }

    public function equals(Vehicle $vehicle): bool
    {
        return $vehicle->getVehiclePlateNumber()->equals($this->vehiclePlateNumber);
    }

    public function park(Location $location): void
    {
        if (null !== $this->location && $this->location->equals($location)) {
            throw new \DomainException('This vehicle is already parked at this location');
        }
        $this->location = $location;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }
}
