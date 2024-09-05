<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Fleet;
use App\Domain\Entity\ValueObject\FleetId;
use App\Domain\Entity\ValueObject\UserId;

interface FleetRepositoryInterface
{
    public function findByFleetId(FleetId $fleetId): Fleet;

    public function update(Fleet $fleet): void;

    public function create(Fleet $fleet): void;

    public function findByUserId(UserId $userId): array;
}