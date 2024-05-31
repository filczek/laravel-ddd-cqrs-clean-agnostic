<?php

declare(strict_types=1);

namespace DDD\Post\Domain\Events;

use DateTimeImmutable;
use DDD\Post\Domain\ValueObjects\PostContent;
use DDD\Post\Domain\ValueObjects\PostId;
use DDD\Shared\Domain\DomainEvent;
use Symfony\Component\Clock\Clock;

final readonly class PostContentWasChanged implements DomainEvent
{
    public static function to(
        PostContent $new_content,
        PostContent $old_content,
        PostId $id,
    ): self {
        return new self(
            id: $id->toString(),
            new_content: $new_content->toString(),
            old_content: $old_content->toString(),
            occurred_on: Clock::get()->now()->format(DATE_ATOM)
        );
    }

    private function __construct(
        public string $id,
        public string $new_content,
        public string $old_content,
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
