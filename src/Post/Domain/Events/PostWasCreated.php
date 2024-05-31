<?php

declare(strict_types=1);

namespace DDD\Post\Domain\Events;

use DateTimeImmutable;
use DDD\Post\Domain\Post;
use DDD\Shared\Domain\DomainEvent;
use Symfony\Component\Clock\Clock;

final readonly class PostWasCreated implements DomainEvent
{
    public static function with(Post $post): self
    {
        return new self(
            id: $post->id()->toString(),
            version: $post->version()->toString(),
            state: $post->state()->value,
            title: $post->title()->toString(),
            content: $post->content()->toString(),
            occurred_on: Clock::get()->now()->format(DATE_ATOM),
        );
    }

    private function __construct(
        public string $id,
        public string $version,
        public string $state,
        public string $title,
        public string $content,
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
