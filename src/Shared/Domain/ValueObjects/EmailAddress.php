<?php

declare(strict_types=1);

namespace DDD\Shared\Domain\ValueObjects;

use Stringable;

readonly class EmailAddress implements Stringable
{
    public static function create(string $email): static
    {
        // TODO validate this shit

        // TODO must be ASCII (what about SMTPUTF8?), must contain one '@'

        return new static($email);
    }

    public static function from(mixed $value): static
    {
        if ($value instanceof static) {
            return static::create($value->email);
        }

        return static::create($value);
    }

    private function __construct(
        private string $email,
    ) {
    }

    public function address(): string
    {
        return $this->email;
    }

    public function local(): string
    {
        // TODO implement
    }

    public function hasPlusAddress(): bool
    {
        // TODO implement
    }

    public function withoutPlusAddress(): static
    {
        // TODO implement
    }


    public function domain(): string
    {
        // TODO implement
    }

    public function __toString(): string
    {
        return $this->address();
    }
}
