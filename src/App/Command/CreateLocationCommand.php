<?php

namespace App\App\Command;

use App\Domain\Entity\ValueObject\Latitude;
use App\Domain\Entity\ValueObject\Longitude;

readonly class CreateLocationCommand
{
    public function __construct(
        public Longitude $longitude,
        public Latitude $latitude,
    ) {
    }
}
