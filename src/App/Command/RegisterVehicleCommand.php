<?php

namespace App\App\Command;

use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\VehiclePlateNumber;

readonly class RegisterVehicleCommand
{
    public function __construct(
        public FleetId            $fleetId,
        public VehiclePlateNumber $vehiclePlateNumber
    )
    {
    }
}