<?php

namespace App\Domain\Repository;

use App\Domain\Entity\ValueObject\VehiclePlateNumber;
use App\Domain\Entity\Vehicle;

interface VehicleRepositoryInterface
{

    public function getByPlateNumber(VehiclePlateNumber $vehiclePlateNumber): Vehicle;

    public function save(Vehicle $vehicle);
}