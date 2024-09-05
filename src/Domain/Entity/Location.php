<?php

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Latitude;
use App\Domain\Entity\ValueObject\LocationId;
use App\Domain\Entity\ValueObject\Longitude;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'LocationId')]
    protected LocationId $locationId;

    public function __construct(
        #[ORM\Column(type: 'Longitude')]
        private Longitude $longitude,
        #[ORM\Column(type: 'Latitude')]
        private Latitude $latitude,
    ) {
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
