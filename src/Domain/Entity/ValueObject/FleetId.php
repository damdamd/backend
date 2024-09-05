<?php

namespace App\Domain\Entity\ValueObject;

readonly class FleetId
{
    public function __construct(private int $id)
    {
    }

    public function toInt(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function equals(FleetId $fleetId): bool
    {
        return $fleetId->toInt() === $this->id;
    }
}
