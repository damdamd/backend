<?php

namespace App\Infra\Doctrine\Type;

use App\Domain\Entity\ValueObject\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class UserIdType extends Type
{
    const string NAME = 'UserId';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    /**
     * @param UserId $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        return $value->toInt();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): UserId
    {
        return new UserId($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}