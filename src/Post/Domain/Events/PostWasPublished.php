<?php

declare(strict_types=1);

namespace DDD\Post\Domain\Events;

use DateTimeImmutable;
use DDD\Post\Domain\ValueObjects\PostId;
use DDD\Shared\Domain\DomainEvent;
use Symfony\Component\Clock\Clock;

final readonly class PostWasPublished implements DomainEvent
{
    public static function withIdOf(PostId $id): self {
        return new self(
            id: $id->toString(),
            occurred_on: Clock::get()->now()->format(DATE_ATOM)
        );
    }

    private function __construct(
        public string $id,
        public string $occurred_on
    ) {
    }

    public function aggregateId(): string
    {
        return $this->id;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->occurred_on);
    }
}
