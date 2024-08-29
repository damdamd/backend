<?php

namespace App\App\Command;

use App\Domain\ValueObject\Latitude;
use App\Domain\ValueObject\Longitude;

readonly class CreateLocationCommand
{
    public function __construct(
        public Longitude $longitude,
        public Latitude  $latitude
    )
    {
    }
}