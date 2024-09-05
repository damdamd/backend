<?php

namespace App\Infra\Doctrine\Type;

use App\Domain\Entity\ValueObject\VehiclePlateNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class VehiclePlateNumberType extends Type
{
    const string NAME = 'VehiclePlateNumber';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @param VehiclePlateNumber $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): VehiclePlateNumber
    {
        return new VehiclePlateNumber($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}