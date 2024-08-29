<?php

namespace App\Domain\ValueObject;

abstract readonly class AbstractId
{
    public function __construct(private int $id)
    {
    }

    public static function create(int $id): static
    {
        return new static($id);
    }

    public function equals(AbstractId $id): bool
    {
        return $this->toInt() === $id->toInt();
    }

    public function toInt(): int
    {
        return $this->id;
    }
}