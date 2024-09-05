<?php

namespace App\Domain\Entity\ValueObject;

readonly class VehiclePlateNumber
{
    public function __construct(private string $plateNumber)
    {
        // Todo plate validation
    }

    public function __toString(): string
    {
        return $this->plateNumber;
    }

    public function equals(VehiclePlateNumber $vehiclePlateNumber): bool
    {
        // todo check if equality can be done directly on object without this method
        return (string) $vehiclePlateNumber === $this->plateNumber;
    }
}
