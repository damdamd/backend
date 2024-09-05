<?php

namespace App\App\Query;

use App\Domain\Entity\ValueObject\UserId;

readonly class FleetListQuery
{
    public function __construct(public UserId $userId)
    {
    }
}
