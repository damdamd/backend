<?php

namespace App\Infra\Doctrine\Type;

use App\Domain\Entity\ValueObject\Longitude;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class LongitudeType extends Type
{
    const string NAME = 'Longitude';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @param Longitude $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Longitude
    {
        return new Longitude($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}