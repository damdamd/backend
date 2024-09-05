<?php

namespace App\App\Query;

use App\Domain\Entity\ValueObject\FleetId;
use App\Domain\Entity\ValueObject\VehiclePlateNumber;

readonly class LocalizeVehicleQuery
{
    public function __construct(
        public FleetId $fleetId,
        public VehiclePlateNumber $vehiclePlateNumber,
    ) {
    }
}
