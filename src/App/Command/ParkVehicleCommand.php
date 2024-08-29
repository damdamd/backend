<?php

namespace App\App\Command;

use App\Domain\ValueObject\LocationId;
use App\Domain\ValueObject\VehiclePlateNumber;

readonly class ParkVehicleCommand
{
    public function __construct(
        public VehiclePlateNumber $vehiclePlateNumber,
        public LocationId         $locationId
    )
    {
    }
}