<?php

namespace App\Infra\Doctrine\Repository;

use App\Domain\Entity\Fleet;
use App\Domain\Entity\ValueObject\FleetId;
use App\Domain\Entity\ValueObject\UserId;
use App\Domain\Repository\FleetRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<Fleet>
 */
class FleetRepository extends ServiceEntityRepository implements FleetRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fleet::class);
    }

    /**
     * @var Fleet[]
     */
    public static array $fleets = [];

    public function findByFleetId(FleetId $fleetId): Fleet
    {
        return $this->find($fleetId) ?? throw new \DomainException('Fleet not found');
    }

    public function update(Fleet $fleet): void
    {
        $this->getEntityManager()->persist($fleet);
        $this->getEntityManager()->flush();
    }

    public function create(Fleet $fleet): void
    {
        $this->getEntityManager()->persist($fleet);
        $this->getEntityManager()->flush();
    }

    /**
     * @return Fleet[]
     */
    public function findByUserId(UserId $userId): array
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('f')
            ->from(Fleet::class, 'f')
            ->where('f.userId = :userId')
            ->setParameter('userId', $userId->toInt())
            ->getQuery()
            ->getResult();
    }
}
