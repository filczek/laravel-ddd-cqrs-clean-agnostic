<?php

declare(strict_types=1);

namespace DDD\Shared\Domain\ValueObjects;

use InvalidArgumentException;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Stringable;

readonly class Guid implements Stringable
{
    public static function nextIdentity(): static
    {
        return static::from(UuidV4::uuid4()->toString());
    }

    public static function from(mixed $value): static
    {
        if ($value instanceof static) {
            return $value;
        }

        if (is_string($value)) {
            return static::fromString($value);
        }

        throw new InvalidArgumentException('Guid must be a string or an instance of ' . Guid::class);
    }

    public static function fromString(string $value): static
    {
        if (false === UuidV4::isValid($value)) {
            throw new InvalidArgumentException('Guid must be a valid Guid');
        }

        return new static($value);
    }

    private function __construct(
        private string $value
    ) {
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
