<?php

declare(strict_types=1);

namespace DDD\Shared\Domain\ValueObjects;

use InvalidArgumentException;
use Stringable;

readonly class Version implements Stringable
{
    public static function from(mixed $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        if (is_int($value)) {
            return new self($value);
        }

        if (is_string($value)) {
            return self::fromString($value);
        }

        throw new InvalidArgumentException("Version must be an integer or instance of " . Version::class);
    }

    public static function fromString(string $string): self
    {
        if (false === is_numeric($string)) {
            throw new InvalidArgumentException("Version must be a numeric string.");
        }

        // coalesce the string to number
        $version = $string * 1;

        if (false === is_int($version)) {
            throw new InvalidArgumentException("Version must be an integer.");
        }

        return self::from($version);
    }

    public static function create(): self
    {
        return self::from(0);
    }

    private function __construct(
        private int $version
    ) {
    }

    public function next(): self
    {
        return self::from($this->version + 1);
    }

    public function equals(self $other): bool
    {
        return $this->version === $other->version;
    }

    public function toInteger(): int
    {
        return $this->version;
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        return (string) $this->toInteger();
    }
}
