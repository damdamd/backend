<?php

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\FleetId;
use App\Domain\Entity\ValueObject\UserId;
use App\Domain\Entity\ValueObject\VehiclePlateNumber;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Traversable;

#[ORM\Entity]
class Fleet
{
    // Todo find a way to use custom collection
    /** @var PersistentCollection<int,Vehicle> */
    #[ORM\JoinTable(name: 'fleets_vehicles')]
    #[ORM\JoinColumn(name: 'fleet_id', referencedColumnName: 'fleet_id')]
    #[ORM\InverseJoinColumn(name: 'vehicle_plate_number', referencedColumnName: 'vehicle_plate_number')]
    #[ORM\ManyToMany(targetEntity: Vehicle::class)]
    private PersistentCollection $vehicles;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'FleetId')]
    private FleetId $fleetId;

    public function __construct(
        #[ORM\Column(type: 'UserId')]
        private readonly UserId $userId,
    )
    {
    }

    public function addVehicle(Vehicle $vehicle): void
    {
        $this->vehicles->add($vehicle);
    }

    public function ownsVehicle(Vehicle $expectedVehicle): bool
    {
        foreach ($this->vehicles as $vehicle) {
            if ($vehicle->equals($expectedVehicle)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Traversable<Vehicle>
     */
    public function getVehicles(): Traversable
    {
        return $this->vehicles;
    }

    public function contains(VehiclePlateNumber $vehiclePlateNumber): bool
    {
        foreach ($this->vehicles as $vehicle) {
            if ($vehicle->getVehiclePlateNumber()->equals($vehiclePlateNumber)) {
                return true;
            }
        }
        return false;
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