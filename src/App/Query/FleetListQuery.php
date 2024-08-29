<?php

namespace App\App\Query;

use App\Domain\ValueObject\UserId;

readonly class FleetListQuery
{
    public function __construct(public UserId $userId)
    {
    }
}