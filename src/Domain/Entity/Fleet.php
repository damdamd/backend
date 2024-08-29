<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\VehiclePlateNumber;

class Fleet
{
    /** @var Vehicle[] */
    private array $vehicles = [];
    private readonly FleetId $fleetId;

    public function __construct(
        private readonly UserId $userId,
    )
    {
    }

    public function addVehicle(Vehicle $vehicle): void
    {
        $this->vehicles[(string)$vehicle->getVehiclePlateNumber()] = $vehicle;
    }

    public function ownsVehicle(Vehicle $vehicle): bool
    {
        return in_array($vehicle, $this->vehicles);
    }

    /**
     * @return Vehicle[]
     */
    public function getVehicles(): array
    {
        return $this->vehicles;
    }

    public function contains(VehiclePlateNumber $vehiclePlateNumber): bool
    {
        return isset($this->vehicles[(string)$vehiclePlateNumber]);
    }

    public function getFleetId(): FleetId
    {
        return $this->fleetId;
    }

    public function belongsTo(UserId $userId): bool
    {
        return $this->userId->equals($userId);
    }
}