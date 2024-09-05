<?php

namespace App\Infra\Doctrine\Type;

use App\Domain\Entity\ValueObject\Latitude;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class LatitudeType extends Type
{
    public const string NAME = 'Latitude';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @param Latitude $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Latitude
    {
        return new Latitude($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
