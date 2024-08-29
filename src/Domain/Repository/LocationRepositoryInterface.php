<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Location;
use App\Domain\ValueObject\LocationId;

interface LocationRepositoryInterface
{
    public function getByLocationId(LocationId $locationId): Location;

    public function create(Location $location): void;
}