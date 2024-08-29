<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Latitude;
use App\Domain\ValueObject\LocationId;
use App\Domain\ValueObject\Longitude;

readonly class Location
{
    private LocationId $locationId;

    public function __construct(
        private Longitude $longitude,
        private Latitude  $latitude,
    )
    {
    }

    public function equals(Location $location): bool
    {
        return $this->locationId->equals($location->getLocationId());
    }

    public function getLocationId(): LocationId
    {
        return $this->locationId;
    }

    public function getLongitude(): Longitude
    {
        return $this->longitude;
    }

    public function getLatitude(): Latitude
    {
        return $this->latitude;
    }
}