<?php

namespace App\Infra\Doctrine\Repository;

use App\Domain\Entity\ValueObject\VehiclePlateNumber;
use App\Domain\Entity\Vehicle;
use App\Domain\Repository\VehicleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DomainException;

class VehicleRepository extends ServiceEntityRepository implements VehicleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function getByPlateNumber(VehiclePlateNumber $vehiclePlateNumber): Vehicle
    {
        return $this->find(['vehiclePlateNumber' => $vehiclePlateNumber]) ?? throw new DomainException('Vehicle not found');
    }

    public function save(Vehicle $vehicle): void
    {
        $this->getEntityManager()->persist($vehicle);
        $this->getEntityManager()->flush();
    }
}
