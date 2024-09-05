<?php

namespace App\App\Command;

use App\Domain\Entity\ValueObject\LocationId;
use App\Domain\Entity\ValueObject\VehiclePlateNumber;

readonly class ParkVehicleCommand
{
    public function __construct(
        public VehiclePlateNumber $vehiclePlateNumber,
        public LocationId $locationId,
    ) {
    }
}
