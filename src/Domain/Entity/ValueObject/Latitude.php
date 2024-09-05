<?php

namespace App\Domain\Entity\ValueObject;

readonly class Latitude
{
    public const string REGEX = '#^([-+])??(90(\.0+?)??|([0-8]??\d)(\.\d+?)??)$#';

    public function __construct(public string $latitude)
    {
        $this->validate($this->latitude);
    }

    public function __toString(): string
    {
        return $this->latitude;
    }

    private function validate(string $latitude): void
    {
        // Todo cover with tests
        if (1 !== preg_match(self::REGEX, $latitude)) {
            throw new \DomainException('Invalid latitude format');
        }
    }
}
