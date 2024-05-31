<?php

declare(strict_types=1);

namespace DDD\Post\Domain\Events;

use DateTimeImmutable;
use DDD\Post\Domain\ValueObjects\PostId;
use DDD\Post\Domain\ValueObjects\PostTitle;
use DDD\Shared\Domain\DomainEvent;
use Symfony\Component\Clock\Clock;

final readonly class PostTitleWasChanged implements DomainEvent
{
    public static function to(
        PostTitle $new_title,
        PostTitle $old_title,
        PostId $id,
    ): self {
        return new self(
            id: $id->toString(),
            new_title: $new_title->toString(),
            old_title: $old_title->toString(),
            occurred_on: Clock::get()->now()->format(DATE_ATOM)
        );
    }

    private function __construct(
        public string $id,
        public string $new_title,
        public string $old_title,
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
