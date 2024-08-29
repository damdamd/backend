<?php

namespace App\App\Query;

use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\VehiclePlateNumber;

readonly class LocalizeVehicleQuery
{
    public function __construct(
        public FleetId $fleetId,
        public VehiclePlateNumber $vehiclePlateNumber
    )
    {
    }
}