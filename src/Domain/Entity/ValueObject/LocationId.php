<?php

namespace App\Domain\Entity\ValueObject;

readonly class LocationId
{
    public function __construct(private int $locationId)
    {
    }

    public function toInt(): int
    {
        return $this->locationId;
    }

    public function equals(LocationId $locationId): bool
    {
        return $locationId->toInt() === $this->locationId;
    }

    public function __toString(): string
    {
        return (string) $this->locationId;
    }
}
