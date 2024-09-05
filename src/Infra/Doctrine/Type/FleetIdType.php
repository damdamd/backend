<?php

namespace App\Infra\Doctrine\Type;

use App\Domain\Entity\ValueObject\FleetId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

{}
class FleetIdType extends Type
{
    const string NAME = 'FleetId';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    /**
     * @param FleetId $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        return $value->toInt();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): FleetId
    {
        return new FleetId($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}