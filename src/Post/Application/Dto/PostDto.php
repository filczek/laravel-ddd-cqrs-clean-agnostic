<?php

declare(strict_types=1);

namespace DDD\Post\Application\Dto;

use DDD\Post\Domain\Post;
use DDD\Shared\Application\Dto\Dto;

final readonly class PostDto extends Dto
{
    public static function fromPost(Post $post): self
    {
        return new self(
            id: $post->id()->toString(),
            version: $post->version()->toString(),
            state: $post->state()->value,
            title: $post->title()->toString(),
            content: $post->content()->toString(),
            created_at: $post->createdAt()->format(DATE_ATOM),
            published_at: $post->publishedAt()?->format(DATE_ATOM),
        );
    }

    public function __construct(
        public string $id,
        public string $version,
        public string $state,
        public string $title,
        public string $content,
        public string $created_at,
        public ?string $published_at
    ) {
    }
}
