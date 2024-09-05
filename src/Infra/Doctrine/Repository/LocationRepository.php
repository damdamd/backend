<?php

namespace App\Infra\Doctrine\Repository;

use App\Domain\Entity\Location;
use App\Domain\Entity\ValueObject\LocationId;
use App\Domain\Repository\LocationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<Location>
 */
class LocationRepository extends ServiceEntityRepository implements LocationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function findByLocationId(LocationId $locationId): Location
    {
        return $this->find($locationId) ?? throw new \DomainException('Location not found');
    }

    public function create(Location $location): void
    {
        $this->getEntityManager()->persist($location);
        $this->getEntityManager()->flush();
    }
}
