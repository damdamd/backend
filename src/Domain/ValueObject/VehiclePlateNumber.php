<?php

namespace App\Domain\ValueObject;

readonly class VehiclePlateNumber
{
    public function __construct(private string $plateNumber)
    {
        // Todo plate validation
    }

    public static function create(string $plateNumber): static
    {
        return new static($plateNumber);
    }

    public function __toString(): string
    {
        return $this->plateNumber;
    }
}