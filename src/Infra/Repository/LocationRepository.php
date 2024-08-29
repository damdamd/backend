<?php

namespace App\Infra\Repository;

use App\Domain\Entity\Location;
use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\ValueObject\LocationId;
use DomainException;

class LocationRepository implements LocationRepositoryInterface
{

    /**
     * @var Location[]
     */
    public static array $locations = [];

    public function getByLocationId(LocationId $locationId): Location
    {
        return self::$locations[$locationId->toInt()] ?? throw new DomainException('Location not found');
    }

    public function create(Location $location): void
    {
        $locationId = new LocationId(count(static::$locations));
        $reflectionObject = new \ReflectionObject($location);
        $property = $reflectionObject->getProperty('locationId');
        $property->setValue($location, $locationId);
        static::$locations[$locationId->toInt()] = $location;

    }
}