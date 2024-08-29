<?php

namespace App\Infra\Repository;

use App\Domain\Entity\Fleet;
use App\Domain\Repository\FleetRepositoryInterface;
use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\UserId;
use DomainException;
use ReflectionObject;

class FleetRepository implements FleetRepositoryInterface
{
    /**
     * @var Fleet[]
     */
    public static array $fleets = [];

    public function getByFleetId(FleetId $fleetId): Fleet
    {
        return self::$fleets[$fleetId->toInt()] ?? throw new DomainException('Fleet not found');
    }

    public function update(Fleet $fleet): void
    {
        static::$fleets[$fleet->getFleetId()->toInt()] = $fleet;
    }

    public function create(Fleet $fleet): void
    {
        $fleetId = new FleetId(count(static::$fleets));
        $reflectionObject = new ReflectionObject($fleet);
        $property = $reflectionObject->getProperty('fleetId');
        $property->setValue($fleet, $fleetId);
        static::$fleets[$fleetId->toInt()] = $fleet;
    }

    public function findByUserId(UserId $userId): array
    {
        return array_filter(static::$fleets, fn(Fleet $value) => $value->belongsTo($userId));
    }
}
