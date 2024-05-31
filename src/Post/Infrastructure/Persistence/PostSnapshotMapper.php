<?php

declare(strict_types=1);

namespace DDD\Post\Infrastructure\Persistence;

use DDD\Post\Domain\Post;

final class PostSnapshotMapper
{
    public static function fromPost(Post $post): PostSnapshot
    {
        return new PostSnapshot(
            id: $post->id()->toString(),
            version: $post->version()->toString(),
            state: $post->state()->value,
            title: $post->title()->toString(),
            content: $post->content()->toString(),
            created_at: $post->createdAt()->format(DATE_ATOM),
            published_at: $post->publishedAt()?->format(DATE_ATOM),
            deleted_at: $post->deletedAt()?->format(DATE_ATOM),
        );
    }
}
