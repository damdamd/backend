<?php

namespace App\Infra\Doctrine\Type;

use App\Domain\Entity\ValueObject\LocationId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class LocationIdType extends Type
{
    const string NAME = 'LocationId';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    /**
     * @param null|LocationId $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): null|int
    {
        return $value?->toInt();
    }

    /**
     * @param null|int $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): null|LocationId
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