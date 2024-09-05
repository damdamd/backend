<?php

namespace App\Infra\Doctrine\Type;

use App\Domain\Entity\ValueObject\LocationId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class LocationIdType extends Type
{
    public const string NAME = 'LocationId';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    /**
     * @param LocationId|null $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        return $value?->toInt();
    }

    /**
     * @param int|null $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?LocationId
    {
        if (null === $value) {
            return null;
        }

        return new LocationId($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
