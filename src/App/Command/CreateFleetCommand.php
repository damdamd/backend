<?php

namespace App\App\Command;

use App\Domain\ValueObject\UserId;

readonly class CreateFleetCommand
{
    public function __construct(
        public UserId $userId
    )
    {
    }
}