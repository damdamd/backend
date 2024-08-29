<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Vehicle;
use App\Domain\ValueObject\VehiclePlateNumber;

interface VehicleRepositoryInterface
{

    public function getByPlateNumber(VehiclePlateNumber $vehiclePlateNumber): Vehicle;

    public function save(Vehicle $vehicle);
}