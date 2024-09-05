<?php

namespace App\Domain\Entity\ValueObject;

class UserId
{
    public function __construct(private int $userId)
    {
    }

    public function toInt(): int
    {
        return $this->userId;
    }

    public function equals(UserId $userId): bool
    {
        return $userId->toInt() === $this->userId;
    }
}