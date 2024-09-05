<?php

namespace App\Domain\Entity\Enum;

enum VehicleType: string
{
    case CAR = 'car';
    case TRUCK = 'truck';
    case MOTOCYCLE = 'motocycle';
    case OTHER = 'other';
}