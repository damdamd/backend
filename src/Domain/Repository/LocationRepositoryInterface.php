<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Location;
use App\Domain\Entity\ValueObject\LocationId;

interface LocationRepositoryInterface
{
    public function findByLocationId(LocationId $locationId): Location;

    public function create(Location $location): void;
}
