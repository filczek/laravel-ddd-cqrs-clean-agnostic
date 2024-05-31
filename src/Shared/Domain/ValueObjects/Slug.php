<?php

declare(strict_types=1);

namespace DDD\Shared\Domain\ValueObjects;

use Illuminate\Support\Str;
use Stringable;

readonly class Slug implements Stringable
{
    public static function from(mixed $value): static
    {
        if ($value instanceof static) {
            return self::from($value->value);
        }

        $value = Str::slug($value);
        return new static($value);
    }

    private function __construct(
        private string $value,
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
