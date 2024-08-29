<?php

namespace App\Domain\ValueObject;

class Longitude
{
    const string REGEX = '#^([-+])??(180(\.0+?)??|(1[0-7]|\d)\d??(\.\d+?)??)$#';

    public function __construct(public string $longitude)
    {
        $this->validate($this->longitude);
    }

    public function __toString(): string
    {
        return $this->longitude;
    }

    private function validate(string $longitude): void
    {
        // Todo cover with tests
        if (1 !== preg_match(self::REGEX, $longitude)) {
            throw new \DomainException('Invalid longitude format');
        }
    }
}