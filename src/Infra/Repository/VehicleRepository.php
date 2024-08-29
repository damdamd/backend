<?php

namespace App\Infra\Repository;

use App\Domain\Entity\Vehicle;
use App\Domain\Repository\VehicleRepositoryInterface;
use App\Domain\ValueObject\VehiclePlateNumber;
use DomainException;

class VehicleRepository implements VehicleRepositoryInterface
{
    /**
     * @var Vehicle[]
     */
    public static array $vehicles = [];

    public function getByPlateNumber(VehiclePlateNumber $vehiclePlateNumber): Vehicle
    {
        return self::$vehicles[(string)$vehiclePlateNumber] ?? throw new DomainException('Vehicle not found');
    }

    public function save(Vehicle $vehicle): void
    {
        static::$vehicles[(string)$vehicle->getVehiclePlateNumber()] = $vehicle;
    }
}
