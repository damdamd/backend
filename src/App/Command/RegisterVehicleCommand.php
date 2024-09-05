<?php

namespace App\App\Command;

use App\Domain\Entity\ValueObject\FleetId;
use App\Domain\Entity\ValueObject\VehiclePlateNumber;

readonly class RegisterVehicleCommand
{
    public function __construct(
        public FleetId $fleetId,
        public VehiclePlateNumber $vehiclePlateNumber,
    ) {
    }
}
