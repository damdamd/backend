<?php

namespace App\App\Command;

use App\Domain\Entity\Enum\VehicleType;
use App\Domain\Entity\ValueObject\VehiclePlateNumber;

readonly class CreateVehicleCommand
{
    public function __construct(
        public VehiclePlateNumber $vehiclePlateNumber,
        public VehicleType $vehicleType,
    ) {
    }
}
