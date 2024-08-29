<?php

namespace App\App\Query;

use App\Domain\Entity\Fleet;
use App\Domain\Repository\FleetRepositoryInterface;

readonly class FleetList
{
    public function __construct(private FleetRepositoryInterface $fleetRepository)
    {

    }

    /**
     * @return Fleet[]
     */
    public function __invoke(FleetListQuery $query): array
    {
        return $this->fleetRepository->findByUserId($query->userId);
    }
}